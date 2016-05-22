<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Asset\AssetLoader;
use App\Http\Requests;
use App\Models\DB\Project;
use App\Models\Business\CategoryBusiness;
use App\Models\Business\CrudProjectBusiness;
use App\Models\Enums\EnumCapabilities;
use Auth;
use Gate;


class ProjectController extends Controller
{

	public function __construct(){
		$this->middleware('auth')->only(['create','store']);
	}

    public function view($id){
    	$project = Project::findORFail($id);
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
            return redirect()->route('admin.home');
        } else {
            return back()->withInput()->withErrors($crudBusiness->getValidator());
        }
    }

    public function edit($id){
        try{
            $project = Project::findOrFail($id);
            AssetLoader::register(['editProject.js'],['admin.css'],['FileUpload']);
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
                return redirect()->route('admin.home');
            } else {
                return back()->withErrors($projectBusiness->getValidator())->withInput();
            }
        } catch(ModelNotFoundException $e){
            return $this->notFound();
        }
    }
}
