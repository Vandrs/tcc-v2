<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Asset\AssetLoader;
use App\Http\Requests;
use App\Models\Business\ProjectBusiness;
use Auth;

class AdminController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }

    public function home(){
        $user = Auth::user();
        $lastUpdatedProjects = $user->projectsAsOwner()->sortByDesc('updated_at')->take(4)->values();
        $projectBusiness = new ProjectBusiness();
        $featuredProjects = $projectBusiness->getFeaturedProjectsForUser($user);
        AssetLoader::register([],['admin.css']);
        $data = [
            'user'             => $user,
            'userProjects'     => $lastUpdatedProjects,
            'featuredProjects' => $featuredProjects
        ];
    	return view('admin.home',$data);
    }
}
