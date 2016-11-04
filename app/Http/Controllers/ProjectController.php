<?php

namespace App\Http\Controllers;

use App\Models\Elastic\ElasticSearchProject;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Asset\AssetLoader;
use App\Http\Requests;
use App\Utils\Utils;
use App\Utils\StringUtil;
use App\Utils\UrlUtil;
use App\Models\DB\Project;
use App\Models\DB\Category;
use App\Models\Business\CategoryBusiness;
use App\Models\Business\CrudProjectBusiness;
use App\Models\Business\ProjectFollowerBusiness;
use App\Models\Enums\EnumCapabilities;
use App\Models\Elastic\Models\ElasticProject;
use Gate;
use Log;
use Auth;
use Config;

class ProjectController extends Controller
{

	public function __construct(){
		$this->middleware('auth')->except(['view']);
	}

    public function view(Request $request, $path){
        try{
            $id = UrlUtil::getIdByUrlPath($path);
            $project = ElasticProject::findById($id);
        } catch(ModelNotFoundException $e){
            return $this->notFound();
        }
        AssetLoader::register(
            ["projectPage.js","projectRating.js","viewProject.js","disqus.js"],
            [],
            ["LightGallery","StarRating"]
        );

        $description = $project->description ? StringUtil::limitaCaracteres(strip_tags($project->description), 160, '...') : '';

        $coverImage =  $project->images->sortByDesc(function($image){return $image->cover;})->values()->first();

        if ($coverImage) {
            $imageUrl = $coverImage->getImageUrl(['w' => 400]);
        } else {
            $imageUrl = '';
        }

        $ogData = [
            'title'       => $project->title,
            'description' => $description,
            'site_name'   => Config::get('app.app_name'),
            'type'        => 'website',
            'locale'      => 'pt_BR',
            'url'         => $project->url,
            'image'       => $imageUrl
        ];

    	return view('project.view',[
            'page_title'       => $project->title,
            'titleRowClass'    => 'text-center',
            'page_description' => $description,
            'canonical'        => $project->url,
            'project'          => $project, 
            'disqus_page_url'  => $project->url, 
            'discus_page_id'   => 'project-'.$id,
            'showAddThis'      => true,
            'og_data'          => $ogData
        ]);
    }

    public function create(){
    	$categories = CategoryBusiness::getCategoriesForDropDown();
        AssetLoader::register(['createProject.js'],['admin.css'],['FileUpload','TinyMce']);
    	return view('project.create', [
    		'categories' => $categories,
    		'page_title' => 'Novo Projeto'
    	] );
    }

    public function store(Request $request){
        $crudBusiness = new CrudProjectBusiness;
        if($project = $crudBusiness->create($request->except(["_token"]),Auth::user())){
            $request->session()->flash('msg','Projeto incluído com sucesso');
            $request->session()->flash('class_msg','alert-success');
            return redirect()->route('admin.user.projects');
        } else {
            return back()->withInput()->withErrors($crudBusiness->getValidator());
        }
    }

    public function edit($id){
        try{
            $project = Project::findOrFail($id);
            AssetLoader::register(['editProject.js','deleteProject.js'],['admin.css'],['FileUpload','TinyMce']);
            if(Gate::denies(EnumCapabilities::UPDATE_PROJECT, $project)){
                return $this->notAllowed();
            }
            return view('project.update',[
                'page_title' => 'Editar Projeto',
                'project' => $project,
                'categories' => CategoryBusiness::getCategoriesForDropDown()
            ]);
        } catch(ModelNotFoundException $e){
            return $this->notFound();
        }
    }

    public function update(Request $request, $id){
        try{
            $project = Project::findOrFail($id);
            if(Gate::denies(EnumCapabilities::UPDATE_PROJECT, $project)){
                return $this->notAllowed();
            }
            $projectBusiness = new CrudProjectBusiness();
            if($projectBusiness->update($project, $request->only(['title','description','category_id','urls']))){
                CrudProjectBusiness::dispatchNotificationJob($project);
                $request->session()->flash('msg','Projeto alterado com sucesso');
                $request->session()->flash('class_msg','alert-success');
                return redirect()->route('admin.user.projects');
            } else {
                return back()->withErrors($projectBusiness->getValidator())->withInput();
            }
        } catch(ModelNotFoundException $e){
            return $this->notFound();
        }
    }

    public function delete(Request $request, $id){
        try{
            $project = Project::findOrFail($id);
            if(Gate::denies(EnumCapabilities::DELETE_PROJECT, $project)){
                return $this->notAllowed();
            }
            $projectBusiness = new CrudProjectBusiness;
            if($projectBusiness->delete($project)){
                $request->session()->flash('msg', 'Projeto excluído com sucesso');
                $request->session()->flash('class_msg', 'alert-success');
                return redirect()->route('admin.user.projects');
            } else {
                return $this->unexpectedError("Não foi possível excluir o projeto!<br />Tente novamente mais tarde e se o erro persistir entre em contato com o administrador do sistema.");
            }
        } catch(ModelNotFoundException $e){
            return $this->notFound();
        } catch(\Exception $e){
            Log::error(Utils::getExceptionFullMessage($e));
            return $this->unexpectedError();
        }
    }

    public function userProjects(Request $request){
        $page = $request->input('page',1);
        $q = $request->get('q',null);
        $categoryid = $request->get('category_id',null);
        if($categoryid){
            $filters = ["category_id" => $categoryid];
        } else {
            $filters = [];
        }
        $searchProject = new ElasticSearchProject;
        $projects = $searchProject->searchUserProjects(Auth::user(), $q, $filters, $page, 8);
        if($q){
            $projects->appends(['q' => $q]);
        }
        if(!empty($filters)){
            foreach($filters as $key => $value){
                $projects->appends([$key => $value]);
            }
        }
        $projects->setPath(route('admin.user.projects'));
        $data = [
            "categories"         => Category::orderBy('name','ASC')->get(),
            "searchTerm"         => $q,
            "selectedCategoryId" => $categoryid,
            "page_title"         => 'Meus Projetos',
            "projects"           => $projects
        ];
        AssetLoader::register(
            ['projectRating.js','deleteProject.js'],
            ['admin.css'],
            ['StarRating']
        );
        return view('project.user-projects',$data);
    }

    public function userFollowingProjects(Request $request)
    {
        $page = $request->input('page',1);
        $q = $request->get('q',null);
        $categoryid = $request->get('category_id',null);
        if($categoryid){
            $filters = ["category_id" => $categoryid];
        } else {
            $filters = [];
        }
        $searchProject = new ElasticSearchProject;
        $projects = $searchProject->searchUserFollowingProjects(Auth::user(), $q, $filters, $page, 8);
        if($q){
            $projects->appends(['q' => $q]);
        }
        if(!empty($filters)){
            foreach($filters as $key => $value){
                $projects->appends([$key => $value]);
            }
        }
        $projects->setPath(route('admin.user.projects.following'));
        $data = [
            "categories"         => Category::orderBy('name','ASC')->get(),
            "searchTerm"         => $q,
            "selectedCategoryId" => $categoryid,
            "page_title"         => 'Projetos que sigo',
            "projects"           => $projects
        ];
        AssetLoader::register(
            ['projectRating.js','deleteProject.js'],
            ['admin.css'],
            ['StarRating']
        );
        return view('project.user-projects-folowing',$data);
    }

    public function follow(Request $request, $id){
        try{
            $project = Project::findOrFail($id);
            if(Gate::denies(EnumCapabilities::FOLLOW_PROJECT, $project)){
                throw new \Exception("Usuário já está vinculado ao projeto");
            }
            if(ProjectFollowerBusiness::follow(Auth::user(),$project)){
                CrudProjectBusiness::dispathElasticJob($project);
            }
            $html = view('project.partials.followers',['followers' => $project->getFollowers()])->render();
            return json_encode(['status' => 1,'class_msg' => 'alert-success', 'html' => $html]);
        } catch (\Exception $e){ 
            Log::error(Utils::getExceptionFullMessage($e));    
            return $this->ajaxUnexpectedError(null, $e->getMessage());
        }
        
    }

    public function unFollow(Request $request, $id){
        try{
            $project = Project::findOrFail($id);
            if(ProjectFollowerBusiness::isUserFollowingProject(Auth::user(), $project)){
                ProjectFollowerBusiness::unfollow(Auth::user(), $project);
                CrudProjectBusiness::dispathElasticJob($project);
            }
            $html = view('project.partials.followers',['followers' => $project->getFollowers()])->render();
            return json_encode(['status' => 1,'class_msg' => 'alert-success', 'html' => $html]);
        } catch (\Exception $e){ 
            Log::error(Utils::getExceptionFullMessage($e));    
            return $this->ajaxUnexpectedError(null, $e->getMessage());
        }
    }
}
