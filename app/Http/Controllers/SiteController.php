<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Business\ProjectBusiness;
use App\Models\Elastic\ElasticSearchProject;
use App\Models\DB\Category;
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
        $categoryid = $request->get('category_id',null);
    	$page = $request->get('page',1);
    	$size = 9;
		$searchProject = new ElasticSearchProject;
    	if($q || $categoryid){
            if($categoryid){
               $filters = ["category_id" => $categoryid];
            } else {
               $filters = [];
            }
    		$projects = $searchProject->searchProject($q,$filters,[],$size,$page);
            if($q){
                $projects->appends(['q' => $q]);    
            }
            if(!empty($filters)){
                foreach($filters as $key => $value){
                    $projects->appends([$key => $value]);
                }
            }
    	} else {
    		$projects = $searchProject->getTopRatedProjects($size,$page);
    	}
    	$projects->setPath(route('search'));
        $categories = Category::orderBy('name','ASC')->get();
    	return view('project.search-result', [
                        'projects'           => $projects, 
                        'searchTerm'         => $q,
                        'selectedCategoryId' => $categoryid,
                        'categories'         => $categories
                    ]);
    }
}
