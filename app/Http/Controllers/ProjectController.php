<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Asset\AssetLoader;
use App\Http\Requests;
use App\Models\DB\Project;
use App\Models\Business\CategoryBusiness;
use App\Models\Business\CrudProjectBusiness;
use Auth;


class ProjectController extends Controller
{

	public function __construct(){
		$this->middleware('auth')->only(['create','store']);
	}
    public function getPredictions($userId){
    	return view('predictions',$data);
    }

    public function view($id){
    	$project = Project::findORFail($id);
    	return view('project.view',['project' => $project]);
    }

    public function create(){
    	$categories = CategoryBusiness::getCategoriesForDropDown();
    	AssetLoader::register(['project.js'],['admin.css'],['FileUpload']);
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
}
