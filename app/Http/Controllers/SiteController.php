<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\DB\User;
use App\Models\DB\Project;

class SiteController extends Controller
{
    public function home(){
    	return view('site.welcome');
    }
}
