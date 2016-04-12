<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\DB\User;
use App\Models\Business\SlopeOne;

class ProjectController extends Controller
{
    public function getPredictions($userId){
    	$user = User::findORFail($userId);
    	$slopeOne = new SlopeOne();
    	$projects = $slopeOne->getPredictions($user);
    	$data = ['page_tile' => 'Predições', 'user' => $user, 'predictions' => $projects];
    	return view('predictions',$data);
    }
}
