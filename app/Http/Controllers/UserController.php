<?php

namespace App\Http\Controllers;

use App\Models\Elastic\ElasticSearchProject;
use App\Models\Elastic\ElasticSearchUser;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\DB\User;
use App\Models\Business\SlopeOne;
use App\Models\Business\CrudUserBusiness;
use App\Asset\AssetLoader;
use App\Utils\Utils;
use Auth;
use Log;

class UserController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth')->except(['view','viewModal','create', 'save']);
	}	


	public function create(Request $request)
	{
		if (Auth::check()) {
			return redirect()->route('admin.home');
		}
		$user = $request->session()->get('user', null);
		if (empty($user)) {
			$user = old('User', null);
		}
		AssetLoader::register(['createUser.js'],['admin.css'], ['AirDatePicker']);
		return view('user.create',['user' => $user]);
	}

	public function save(Request $request)
	{
		$crudBusiness = new CrudUserBusiness();
		if ($user = $crudBusiness->create($request->get('User'))) {
			Auth::login($user);
			return redirect()->route('admin.home');
		} else {
			return back()->withErrors($crudBusiness->getValidator())->withInput();
		}
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
    		return json_encode(["status" => 1, "html" => view('user.partial-public-profile',['user' => $user, 'showLink' => true])->render()]);
    	} catch (\Exception $e) {
    		Log::error(Utils::getExceptionFullMessage($e));
    		return $this->ajaxNotAllowed();
    	}
    }

	public function profile()
	{
		$user = Auth::user();
		$data = ['page_title' => 'Atualizar Perfil', 'user' => $user];
        AssetLoader::register(['updateUser.js'],['admin.css'], ['AirDatePicker']);
		return view('user.profile',$data);
	}

	public function updateProfile(Request $request)
	{
		$user = Auth::user();
		$userBusiness = new CrudUserBusiness();
		if ($userBusiness->update($user, $request->except('_token'))) {
			$request->session()->flash('msg', 'Perfil alterado com sucesso!');		
			$request->session()->flash('class_msg', 'alert-success');
			return redirect()->route('admin.user.profile');
		} else {
			return back()->withErrors($userBusiness->getValidator())->withInput();
		}
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

	public function addTrelloId(Request $request){
		try {
			$idMember = $request->get('idMember');
			if ($idMember && Auth::user()->update(['trello_token' => $idMember])) {
				$result = ['status' => 1, 'msg' => 'Registro alterado com sucesso', 'alert-success'];
			} else {
				$result = ['status' => 0, 'msg' => 'Falha ao tentar alterar o registro', 'class_msg' => 'alert-danger'];
			}
			return json_encode($result);
		} catch (\Exception $e) {
			Log::error(Utils::getExceptionFullMessage($e));
			return $this->ajaxUnexpectedError();
		}
	}
}
