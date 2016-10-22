<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DB\ProjectValidation;
use App\Models\DB\Project;
use App\Models\Business\ElasticProjectBusiness;

class TesteController extends Controller
{
    public function index()
    {	
        $project = Project::find(21);
        dd(ElasticProjectBusiness::getElasticProjectData($project));
    }
}
