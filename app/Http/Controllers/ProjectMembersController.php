<?php

namespace App\Http\Controllers;

use Auth;
use Log;
use DB;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Utils\Utils;
use App\Asset\AssetLoader;
use App\Models\DB\Project;
use App\Models\DB\User;
use App\Models\DB\UserProject;
use App\Models\Enums\EnumProject;


class ProjectMembersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('projectManager')->except(['invite','acceptInvitation','denyInvitation']);
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
                                return '<a href="'.route('user.view',['id' => $user->id]).'" title="Ver Perfil" data-toggle="tooltip">'.$user->name.'</a>';
                           })->editColumn('role', function($user) use ($userProject) { 
                                return EnumProject::getRoleName($user->role);
                           })->addColumn('actions', function($user) use ($userProject) {
                                $profileLink = route('user.view',['id' => $user->id]);
                                $actions = "<a href='".$profileLink."' data-toggle='tooltip' title='Ver Perfil' class='btn btn-fab btn-fab-mini margin-right-5'><i class='material-icons'>person</i></a>";
                                $removeText = $user->id == $userProject->id ? 'Sair' : 'Remover';
                                $actions .= "<button data-toggle='tooltip' title='".$removeText."' class='btn btn-fab btn-fab-mini'><i class='material-icons'>delete</i></button>";
                                return $actions;
                           })->make(true);
        } catch(\Exception $e) {
            Log::error($e);
            return $this->ajaxUnexpectedError();
        }
    }

    public function invite($id)
    {
        
    }

    public function acceptInvitation($id)
    {

    }

    public function denyInvitation($id)
    {

    }
}
