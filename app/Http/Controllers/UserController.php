<?php

namespace App\Http\Controllers;

use App\Models\Elastic\ElasticSearchProject;
use App\Models\Elastic\ElasticSearchUser;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\DB\User;
use App\Models\Business\SlopeOne;
use App\Asset\AssetLoader;
use App\Utils\Utils;
use Auth;
use Log;

class UserController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth')->except(['view','viewModal']);
	}	

	public function view($id)
	{
    	$user = User::findORFail($id);
    	$slopeOne = new SlopeOne();
    	$projects = $slopeOne->getPredictions($user);
    	$data = ['page_tile' => 'Predições', 'user' => $user, 'predictions' => $projects];
    	return view('user.view',$data);
    }

    public function viewModal(Request $request)
    {
    	if (!$request->ajax()) {
    		return $this->notAllowed();
    	}
    	try {
    		$user = User::findORFail($request->get('id'));
    		return json_encode(["status" => 1, "html" => view('user.partial-public-profile',['user' => $user])->render()]);
    	} catch (\Exception $e) {
    		Log::error(Utils::getExceptionFullMessage($e));
    		return $this->ajaxNotAllowed();
    	}
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
		try {
			$searchUser = new ElasticSearchUser();
			$page = $request->get("page",1);
			$size = $request->get("size",12);
			$usersPaginator = $searchUser->searchUsers($request->all(), $size, $page);
			$usersPaginator->setPath($request->path());
			foreach ($request->all() as $key => $value) {
				$usersPaginator->appends($key, $value);
			}
			return $this->buildHtmlSearchData($usersPaginator);
		} catch (\Exception $e) {
			Log::error(Utils::getExceptionFullMessage($e));
			return $this->ajaxUnexpectedError();
		}
	}

	private function buildHtmlSearchData($paginator)
	{
		$paginatorHtml = $paginator->render();
		$data = [
			"status" 	 	 => 1, 
			"html_users" 	 => view('user.users-project-list',['users' => $paginator->getCollection()])->render(),
			"html_paginator" => is_string($paginatorHtml) ? $paginatorHtml : $paginatorHtml->__toString()
		];
		return $data;
	}
}
