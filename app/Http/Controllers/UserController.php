<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\DB\User;
use Auth;

class UserController extends Controller
{
    public function all(){
    	$users = User::orderBy('name','asc')->get();
    	return view('welcome',['users' => $users]);
    }

    public function loginAs(){

    }

    public function logout(){

    }
}
