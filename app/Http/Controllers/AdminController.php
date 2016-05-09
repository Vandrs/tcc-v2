<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Asset\AssetLoader;
use App\Http\Requests;
use Auth;

class AdminController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }

    public function home(){
    	$data = ['user' => Auth::user()];
    	AssetLoader::register([],['admin.css']);
    	return view('admin.home',$data);
    }
}
