<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Asset\AssetLoader;
use App\Http\Requests;
use App\Models\DB\Project;
use App\Models\Business\CategoryBusiness;


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
    	AssetLoader::register([],['admin.css']);
    	return view('project.create', [
    		'categories' => $categories,
    		'page_title' => 'Novo Projeto'
    	] );
    }

    public function store(Request $request){
        dd($request->all());
    }
}
