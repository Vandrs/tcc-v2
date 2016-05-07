<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Asset\AssetLoader;
use App\Http\Requests;
use App\Models\DB\Project;
use App\Models\DB\Category;


class ProjectController extends Controller
{

	public function __construct(){
		//$this->middleware('auth')->only(['create']);
	}
    public function getPredictions($userId){
    	return view('predictions',$data);
    }

    public function view($id){
    	$project = Project::findORFail($id);
    	return view('project.view',['project' => $project]);
    }

    public function create(){
    	$categories = Category::orderBy('name','ASC');
    	AssetLoader::register([],['admin.css']);
    	return view('project.create', ['categories' => $categories] );
    }
}
