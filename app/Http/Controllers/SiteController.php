<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Business\ProjectBusiness;
use App\Models\Elastic\ElasticSearchProject;
use App\Models\DB\Category;
use Illuminate\Http\Response;
use App\Asset\AssetLoader;
use Auth;
use Config;

class SiteController extends Controller
{
    public function home(Request $request){

        if(Auth::check()){
    		$user = Auth::user();
    	} else {
    		$user = null;
    	}
    	$business = new ProjectBusiness();
    	$projects = $business->getFeaturedProjects($user);
        AssetLoader::register(['projectRating.js'],[],['StarRating']);
        $ogData = [
            'title'       => trans('pages.home.title'),
            'description' => trans('pages.home.description'),
            'site_name'   => Config::get('app.app_name'),
            'type'        => 'website',
            'locale'      => 'pt_BR',
            'url'         => Config::get('app.url'),
            'image'       => ''
        ];
        $data = [
            'page_description'  => trans('pages.home.description'),
            'page_keywords'     => trans('pages.home.keywords'),
            'og_data'           => $ogData,
            'canonical'         => Config::get('app.url'),
            'projects'          => $projects,
        ];

    	return view('site.welcome',$data);
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
        AssetLoader::register(['projectRating.js'],[],['StarRating']);
        $ogData = [
            'title'       => trans('pages.search.title'),
            'description' => trans('pages.search.description'),
            'site_name'   => Config::get('app.app_name'),
            'type'        => 'website',
            'locale'      => 'pt_BR',
            'url'         => route('search'),
            'image'       => ''
        ];
    	return view('project.search-result', [
                        'projects'           => $projects, 
                        'searchTerm'         => $q,
                        'selectedCategoryId' => $categoryid,
                        'categories'         => $categories,
                        'page_description'   => trans('pages.search.description'),
                        'page_keywords'      => trans('pages.search.keywords'),
                        'page_title'         => trans('pages.search.title'),
                        'titleRowClass'      => 'text-center',
                        'og_data'            => $ogData,
                        'canonical'           => route('search'),
                        'noIndex'            => true
                    ]);
    }

    public function page404(){
        return response(view('errors.404')->render(), Response::HTTP_NOT_FOUND);
    }

    public function error(Request $request)
    {
        $data = [
            'page_title'    => 'Ooops ocorreu um erro inesperado!!!',
            'titleRowClass' => 'text-left',
            'noIndex'       => true,
            'msg'           => $request->session()->get('msg', null)
        ];
        return response(view('errors.error',$data)->render(), Response::HTTP_BAD_REQUEST);
    }
}
