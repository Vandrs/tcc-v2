<?php 

namespace App\Models\Business;

use App\Models\DB\Project;
use Validator;

class ProjectManagementBusiness {

	private $validator;

	public  function addProjectBoardId($boardId, Project $project){
		$data = ['board_id' => $boardId];
		$this->validator = Validator::make($data, $this->validation(), $this->messages());
		if($this->validator->passes()){
			if(Project::where('trello_board_id',$boardId)->exists()){
				$this->validator->errors()->add('board_id','O Quadro selecionado já está sendo utilizado em outro projeto.');
			} else {
				return $project->update(['trello_board_id' => $boardId]);
			}
		}
		return false;
	}

	public function validation(){
		return ['board_id' => 'required'];
	}

	public function messages(){
		return ['board_id.required' => trans('custom_messages.unexpected_error')];
	}

	public function getValidator(){
		return $this->validator;
	}

}

