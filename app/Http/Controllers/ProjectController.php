<?php

namespace App\Http\Controllers;

use App\Models\Elastic\ElasticSearchProject;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Asset\AssetLoader;
use App\Http\Requests;
use App\Models\DB\Project;
use App\Models\DB\Category;
use App\Models\Business\CategoryBusiness;
use App\Models\Business\CrudProjectBusiness;
use App\Models\Enums\EnumCapabilities;
use App\Models\Elastic\Models\ElasticProject;
use Auth;
use Gate;


class ProjectController extends Controller
{

	public function __construct(){
		$this->middleware('auth')->except(['view']);
	}

    public function view($id){
        try{
            $project = ElasticProject::findById($id);
        } catch(ModelNotFoundException $e){
            $this->notFound();
        }
        AssetLoader::register(["projectPage.js","projectRating.js"],[],["LightGallery","StarRating"]);
    	return view('project.view',['project' => $project]);
    }

    public function create(){
    	$categories = CategoryBusiness::getCategoriesForDropDown();
        AssetLoader::register(['createProject.js'],['admin.css'],['FileUpload']);
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
            AssetLoader::register(['editProject.js','deleteProject.js'],['admin.css'],['FileUpload']);
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
}
