<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\Utils;
use App\Utils\StringUtil;
use App\Asset\AssetLoader;
use App\Models\DB\Project;
use App\Models\Business\ProjectManagementBusiness;
use Log;
use Config;
use Auth;

class ProjectManagementController extends Controller{

	public function __construct(){
		$this->middleware('projectManager');
	}

	public function index($id){
		try{
			$project = Project::findOrFail($id);
			if(empty($project->trello_board_id)){
				return redirect()->route("admin.project.management.first",['id' => $project->id]);
			}
			AssetLoader::register(
				['c3Trello.js','managementLayout.js','projectManagement.js'],
				['admin.css'],
				['Trello','AirDatePicker', 'JqueryUI']
			);
			$data = [
				'page_title'   => 'Gerenciamento',
				'project' 	   => $project,
				'js_variables' => [
					'TRELLO_APP_NAME'  		=> Config::get('trello.app_name'),
					'TRELLO_BOARD_ID'  		=> $project->trello_board_id,
					'PROJECT_NAME'	   		=> $project->title,
					'NEED_TO_GET_ID'   		=> empty(Auth::user()->trello_token),
					'SET_TRELLO_ID_ROUTE'	=> route('users.add.trello-id')
				]
			];
			return view('project.management',$data);
		} catch(\Exception $e) {
			$msg = Utils::getExceptionFullMessage($e);
			Log::error($e);
			return $this->unexpectedError();
		}
	}

	public function firstTimeAccess(Request $request, $id){
		try{
			$project = Project::findOrFail($id);
			if($project->trello_board_id){
				return redirect()->route('admin.project.management',['id' => $project->id]);
			}
			$data = [
				'page_title'   => 'Gerenciamento',
				'project' 	   => $project,
				'js_variables' => [
					'TRELLO_APP_NAME' 		=> Config::get('trello.app_name'),
					'PROJECT_NAME'			=> $project->title,
					'NEED_TO_GET_ID'		=> empty(Auth::user()->trello_token),
					'SET_TRELLO_ID_ROUTE'	=> route('users.add.trello-id')
				]
			];
			AssetLoader::register(['c3Trello.js','trelloProjectSetUp.js'],['admin.css'],['Trello']);
			return view('project.management-first-access',$data);
		} catch(\Exception $e) {
			$msg = Utils::getExceptionFullMessage($e);
			Log::error($e);
			return $this->unexpectedError();
		}
	}	

	public function assignKeys(Request $request, $id){
		try{
			$project = Project::findOrFail($id);
			$business = new ProjectManagementBusiness();
			if($business->addProjectBoardId($request->input('board_id'), $project)){
				$result = [
					"status" 	  => 1,
					"redirect_to" => route('admin.project.management',['id' => $project->id])
				];
			} else {
				$result = [
					"status" 	=> 0,
					"msg" 		=> implode('<br />',$business->getValidator()->errors()->all()),
					"class_msg" => 'alert-danger'
				];
			}
			return json_encode($result);
		} catch(\Exception $e) {
			$msg = Utils::getExceptionFullMessage($e);
			Log::error($e);
			return $this->ajaxUnexpectedError(null,$msg);
		}
	}

}