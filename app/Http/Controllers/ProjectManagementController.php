<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\Utils;
use App\Utils\StringUtil;
use App\Asset\AssetLoader;
use App\Models\DB\Project;
use Log;
use Config;

class ProjectManagementController extends Controller{

	public function __construct(){
		$this->middleware('projectManager');
	}

	public function index($id){

	}

	public function firstTimeAccess(Request $request, $id){
		try{
			$project = Project::findOrFail($id);
			if($project->trello_default_key && $project->trello_board_id){
				return redirect()->route('admin.project.management',['id' => $project->id]);
			}
			$data = [
				'page_title'   => 'Gerenciamento',
				'project' 	   => $project,
				'js_variables' => $this->getJsVariables($project)
			];
			AssetLoader::register([],['admin.css'],['Trello']);
			return view('project.management-first-access',$data);
		} catch(\Exception $e) {
			$msg = Utils::getExceptionFullMessage($e);
			Log::error($e);
			return $this->unexpectedError();
		}
	}	

	public function assignKeys(Request $request, $id){
		dd('keys');
	}

	private function getJsVariables(Project $project){
		return [
			'TRELLO_APP_NAME' 	 		 => Config::get('trello.app_name'),
			'TRELLO_DEFAULT_ACCOUNT'     => Config::get('trello.default_account'),
			'TRELLO_DEFAULT_BOARD_ID'    => StringUtil::toUrl($project->title."-".$project->id)
		];
	}
}