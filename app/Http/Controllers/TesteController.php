<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\DB\User;
use App\Models\DB\Project;
use App\Models\DB\Work;
use App\Models\DB\Graduation;
use App\Models\Business\MailBusiness;
use App\Models\Business\ProjectEmailBusiness;
use App\Models\Business\ElasticUserBusiness;
use App\Models\Business\ElasticExportBusiness;
use App\Models\Elastic\Models\ElasticUser;
use App\Jobs\SendEmailJob;
use Auth;

class TesteController extends Controller
{
    public function index(){
        $user = ElasticUser::findById(19);
        dd($user->skills);
    }

    public function usersLoginList(){
    	$users = User::orderBy('name','ASC')->get();
    	$data = ['users' => $users, 'page_title' => 'Usuários'];
    	return view('test.users',$data);
    }

    public function projectsList(){
    	$projects = Project::orderBy('title','ASC')->get();
    	$data = ['projects' => $projects, 'page_title' => 'Projetos'];
    	return view('test.projects',$data);
    }

    public function loginAs($id){
    	$user = User::findOrFail($id);
    	Auth::login($user);
    	return redirect()->route('admin.home');
    }

    public function logout(){
    	Auth::logout();
    	return redirect()->route('home');
    }
}
