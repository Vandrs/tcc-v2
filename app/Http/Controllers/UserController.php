<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\DB\User;
use App\Models\Business\SlopeOne;


class UserController extends Controller
{
    public function view($id){
    	$user = User::findORFail($id);
    	$slopeOne = new SlopeOne();
    	$projects = $slopeOne->getPredictions($user);
    	$data = ['page_tile' => 'Predições', 'user' => $user, 'predictions' => $projects];
    	return view('user.view',$data);
    }
}
