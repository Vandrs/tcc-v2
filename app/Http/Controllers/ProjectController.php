<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\DB\User;
use App\Models\DB\Project;
use App\Models\Business\SlopeOne;

class ProjectController extends Controller
{
    public function getPredictions($userId){
    	return view('predictions',$data);
    }

    public function view($id){
    	$project = Project::findORFail($id);
    	return view('project.view',['project' => $project]);
    }
}
