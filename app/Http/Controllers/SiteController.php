<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Business\ProjectBusiness;
use App\Models\Elastic\ElasticSearchProject;
use Auth;

class SiteController extends Controller
{
    public function home(){
    	if(Auth::check()){
    		$user = Auth::user();
    	} else {
    		$user = null;
    	}
    	$business = new ProjectBusiness();
    	$projects = $business->getFeaturedProjects($user);
    	return view('site.welcome',['projects' => $projects]);
    }

    public function search(Request $request){	
    	$q = $request->get('q',null);
    	$page = $request->get('page',1);
    	$size = 9;
		$searchProject = new ElasticSearchProject;
    	if($q){
    		$projects = $searchProject->searchProject($q,[],[],$size,$page);
    		$projects->appends(['q' => $q]);
    	} else {
    		$projects = $searchProject->getTopRatedProjects($size,$page);
    	}
    	$projects->setPath(route('search'));
    	return view('project.search-result', ['projects' => $projects, 'searchTerm' => $q]);
    }
}
