<?php

namespace App\Http\Controllers;

use Auth;
use Gate;
use Log;
use DB;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Utils\Utils;
use App\Utils\DateUtil;
use App\Asset\AssetLoader;
use App\Models\DB\Project;
use App\Models\DB\User;
use App\Models\DB\UserProject;
use App\Models\Enums\EnumProject;
use App\Models\Enums\EnumCapabilities;
use App\Models\Business\UserProjectBusiness;
use App\Models\Business\CrudProjectBusiness;


class ProjectMembersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('projectManager')->except(['invite','acceptInvitation','denyInvitation', 'invitations', 'listInvitations']);
    }

    public function index($id)
    {
        try {
            $project = Project::findOrFail($id);
            $data = ['page_title' => 'Gerenciar Usuários','project' => $project];
            AssetLoader::register(['projectMembers.js'],['admin.css'],['DataTables']);
            return view('project.users', $data);
        } catch(\Exception $e) {
            Log::error($e);
            return $this->unexpectedError();
        }
    }

    public function listMembers($id)
    {
        try {
            $project = Project::findOrFail($id);
            $userProject = $project->isMember(Auth::user());
            $roles = [EnumProject::ROLE_CONTRIBUTOR, EnumProject::ROLE_OWNER, EnumProject::ROLE_MENTOR];
            $baseQuery  = User::select([
                            DB::raw('users.*'),
                            DB::raw('user_projects.role'),
                            DB::raw('user_projects.id AS user_project_id'),
                            DB::raw('user_projects.creator')
                          ])        
                          ->join('user_projects','user_projects.user_id','=','users.id')
                          ->where('user_projects.project_id','=',$project->id)
                          ->whereIn('user_projects.role', $roles);

            return Datatables::of($baseQuery)
                           ->editColumn('name', function($user) {
                                return '<a href="'.route('user.view',['id' => $user->id]).'" title="Ver Página" data-toggle="tooltip">'.$user->name.'</a>';
                           })->editColumn('role', function($user) use ($userProject, $project) { 
                                if ($user->id != $userProject->id && Gate::allows(EnumCapabilities::REMOVE_AND_MANAGE_PROJECT_PROFILES, $project)) {
                                    $select = '<select id="role" class="form-control change-role" name="role" data-user-id="'.$user->id.'">';    
                                    foreach(EnumProject::getProjectInviteRoles() as $key => $role){
                                        $select .= '<option '.(($key == $user->role)?"selected":"").' value="'.$key.'">'.$role.'</option>';
                                    }
                                    $select .= "</select>";
                                    return $select;
                                } else {
                                    return EnumProject::getRoleName($user->role);    
                                }
                           })->addColumn('actions', function($user) use ($userProject, $project) {
                                $actions = "<a href='#' data-user-id='".$user->id."' data-toggle='tooltip' title='Ver Perfil' class='view-modal-profile btn btn-fab btn-fab-mini margin-right-5'><i class='material-icons'>person</i></a>";
                                if ($user->id != $userProject->id && Gate::allows(EnumCapabilities::REMOVE_AND_MANAGE_PROJECT_PROFILES, $project)) {
                                    $removeText = 'Remover';
                                    $actions .= "<button data-user-id='".$user->id."'data-toggle='tooltip' title='".$removeText."' class='btn btn-fab btn-fab-mini removeMember'><i class='material-icons'>delete</i></button>";
                                } 
                                return $actions;
                           })->make(true);
        } catch(\Exception $e) {
            Log::error($e);
            return $this->ajaxUnexpectedError();
        }
    }

    public function invite($id, Request $request)
    {
        try{
            $data = $request->except("_token");
            $userProjectBusiness = new UserProjectBusiness();
            if ($userProject = $userProjectBusiness->invite($data)) {
                $result = [
                    "status"    => 1, 
                    "msg"       => "Usuário convidado com sucesso", 
                    "class_msg" => "alert-success"];
            } else {
                $result = [
                    "status"    => 0, 
                    "msg"       => implode("<br />", $userProjectBusiness->getValidator()->errors()->all()),
                    "class_msg" => "alert-danger"
                ];
            }
            return json_encode($result);
        } catch(\Exception $e){
            Log::error(Utils::getExceptionFullMessage($e));
            return $this->ajaxUnexpectedError();
        }   
    }

    

    public function remove(Request $request, $id)
    {
        try {
            $project = Project::findOrFail($id);
            $userProjectBusiness = new UserProjectBusiness();
            if ($userProjectBusiness->removeUser($project, $request->input('user_id'))) {
                $result = [
                    "status"    => 1,
                    "msg"       => 'Usuário removido com sucesso.',
                    "class_msg" => 'alert-success'
                ];
                CrudProjectBusiness::dispathElasticJob($project);
            } else {
                $result = [
                    "status"    => 0,
                    "msg"       => implode("<br />", $userProjectBusiness->getValidator()->errors()->all()),
                    "class_msg" => 'alert-danger'
                ];
            }
            return json_encode($result);
        } catch (\Exception $e){
            Log::error(Utils::getExceptionFullMessage($e));
            return $this->ajaxUnexpectedError();
        }
    }

    public function changeRole(Request $request, $id)
    {
        try {
            $project = Project::findOrFail($id);
            $data = $request->except("_token");
            $userProjectBusiness = new UserProjectBusiness();
            if ($userProjectBusiness->changeRole($project, $data)) {
                $result = [
                    'status' => 1, 
                    'msg'    => 'Perfil alterado com sucesso.', 
                    'class_msg' => 'alert-success'
                ];
                CrudProjectBusiness::dispathElasticJob($project);
            } else {
                $result = [
                    "status"    => 0, 
                    'msg'       => implode("<br />", $userProjectBusiness->getValidator()->errors()->all()), 
                    'class_msg' => 'alert-danger'
                ];
            }
            return json_encode($result);
        } catch (\Exception $e) {
            Log::error(Utils::getExceptionFullMessage($e));
            return $this->ajaxUnexpectedError();
        }
    }

    public function invitations()
    {
        $data = ['page_title' => 'Convites'];
        AssetLoader::register(['projectInvitations.js'],['admin.css'],['DataTables']);
        return view('user.invitations', $data);
    }

    public function listInvitations()
    {
        try {
            $user = Auth::user();
            $baseQuery = Project::select([
                            DB::raw('projects.*'),
                            DB::raw('user_projects.role'),
                            DB::raw('user_projects.temp_role'),
                            DB::raw('user_projects.created_at AS invitation_date'),
                        ])
                        ->join('user_projects', 'projects.id', '=', 'user_projects.project_id')
                        ->where('user_projects.user_id', '=', $user->id)
                        ->where('user_projects.role', '=', EnumProject::ROLE_INVITED);

            return Datatables::of($baseQuery)
                             ->editColumn('invitation_date',function($project){
                                $date = DateUtil::dateTimeInBrazil($project->invitation_date);
                                return $date->format('d/m/Y');
                             })
                             ->addColumn('message',function($project){
                                $projectLink = "<a href='".$project->url."'>".$project->title."</a>";
                                $profile = "<b>".EnumProject::getRoleName($project->temp_role)."</b>";
                                $html = "Você foi convidade para partipar como ".$profile." do projeto ".$projectLink;
                                return $html;
                             })
                             ->addcolumn('actions', function($project){ 
                                $html = "<a data-toggle='tooltip' title='Aceitar Convite' href='".route('admin.project.invidation.accept',['id' => $project->id])."' class='accept btn btn-fab btn-fab-mini margin-right-5'><i class='material-icons'>done</i></a>";
                                $html .= "<a data-toggle='tooltip' title='Rejeitar Convite' href='".route('admin.project.invidation.deny',['id' => $project->id])."' class='deny btn btn-fab btn-fab-mini'><i class='material-icons'>clear</i></a>";
                                return $html;
                             })
                             ->make(true);
        } catch (\Exception $e) {
            Log::error(Utils::getExceptionFullMessage($e));
            return $this->ajaxUnexpectedError();   
        }
    }

    public function acceptInvitation($id)
    {
        try {
            $project = Project::findOrFail($id);
            $userProjectBusiness = new UserProjectBusiness();
            if ($userProjectBusiness->acceptInvitation(Auth::user(), $project)) {
                CrudProjectBusiness::dispathElasticJob($project);
                $result = [
                    "status"    => 1, 
                    'msg'       => "Convite aceito com sucesso", 
                    'class_msg' => 'alert-success'
                ];
            } else {
                $result = [
                    "status"    => 0, 
                    'msg'       => implode("<br />", $userProjectBusiness->getValidator()->errors()->all()), 
                    'class_msg' => 'alert-danger'
                ];
            }
            return json_encode($result);
        } catch (\Exception $e){
            Log::error(Utils::getExceptionFullMessage($e));
            return $this->ajaxUnexpectedError();
        }
    }

    public function denyInvitation($id)
    {
        try {
            $project = Project::findOrFail($id);
            $userProjectBusiness = new UserProjectBusiness();
             if ($userProjectBusiness->denyInvitation(Auth::user(), $project)) {
                $result = [
                    "status"    => 1, 
                    'msg'       => "Convite recusado com sucesso", 
                    'class_msg' => 'alert-success'
                ];
            } else {
                $result = [
                    "status"    => 0, 
                    'msg'       => implode("<br />", $userProjectBusiness->getValidator()->errors()->all()), 
                    'class_msg' => 'alert-danger'
                ];
            }
            return json_encode($result);
        } catch (\Exception $e){
            Log::error(Utils::getExceptionFullMessage($e));
            return $this->ajaxUnexpectedError();
        }
    }
}
