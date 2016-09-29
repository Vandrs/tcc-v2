<?php

namespace App\Http\Controllers;

use App\Models\Elastic\ElasticSearchProject;
use App\Models\Elastic\ElasticSearchUser;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\DB\User;
use App\Models\Business\SlopeOne;
use App\Asset\AssetLoader;
use Auth;

class UserController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth')->except('view');
	}	

	public function view($id)
	{
    	$user = User::findORFail($id);
    	$slopeOne = new SlopeOne();
    	$projects = $slopeOne->getPredictions($user);
    	$data = ['page_tile' => 'Predições', 'user' => $user, 'predictions' => $projects];
    	return view('user.view',$data);
    }

	public function profile()
	{
		$user = Auth::user();
		$slopeOne = new SlopeOne();
		$projects = $slopeOne->getPredictions($user);
		$data = ['page_tile' => 'Predições', 'user' => $user, 'predictions' => $projects];
        AssetLoader::register([],['admin.css']);
		return view('user.profile',$data);
	}

	public function search(Request $request)
	{
		$searchUser = new ElasticSearchUser();
		$users = $searchUser->searchUsers($request->all());
		return json_encode($users);
		dd($users);
	}
}
