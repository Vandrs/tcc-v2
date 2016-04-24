<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\DB\User;
use App\Models\DB\Project;
use Auth;

class UserController extends Controller
{
    public function all(){
    	$users = User::orderBy('name','asc')->get();
    	$projects = Project::orderBy('title','asc')->get();
    	return view('welcome',['users' => $users, 'projects' => $projects]);
    }

    public function loginAs(){

    }

    public function logout(){

    }
}
