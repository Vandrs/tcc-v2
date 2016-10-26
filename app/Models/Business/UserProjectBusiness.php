<?php 

namespace App\Models\Business;

use App\Models\DB\User;
use App\Models\DB\Project;
use App\Models\DB\UserProject;
use App\Models\Enums\EnumProject;
use App\Models\Business\CrudProjectBusiness;
use App\Utils\Utils;
use App\Models\Interfaces\C3Project;
use DB;
use Log;
use Auth;
use Validator;

class UserProjectBusiness
{

	const CREATOR = 1;	
	const NON_CREATOR = 0;

	private $validator;

	public function create(User $user, C3Project $project, $role, $creator = 0, $tempRole = null)
	{	
		return UserProject::create([
			'user_id'	 	 => $user->id,
			'project_id' 	 => $project->id,
			'role' 		 	 => $role,
			'temp_role'  	 => $tempRole,
			'creator'	 	 => $creator,
			'board_assigned' => EnumProject::BOARD_ASSIGNED
		]);
	}

	public function delete(User $user, C3Project $project){
		try{
			$userProject = UserProject::where('user_id', '=', $user->id)
									  ->where('project_id', '=', $project->id)
									  ->firstOrFail();
			return $userProject->delete();
		} catch(\Exception $e){
			Log::error(Utils::getExceptionFullMessage($e));
			return false;
		}
	}

	public function invite($data)
	{
		try{
			$this->validator = Validator::make($data, $this->validation(), $this->messages());
			if($this->validator->fails()){
				return false;
			}
			$user = User::findOrFail($data['user_id']);
			$project = Project::find($data['project_id']);
			$exists = UserProject::where("project_id","=",$project->id)
								 ->where("user_id","=",$user->id)
								 ->exists();
			if($exists){
				$this->validator->errors()
								->add("user_id","Usuário selecionado já está vinculado ao projeto.<br />Caso ele não seja um membro do Projeto ele pode ainda não ter aceitado o convite");
				return false;
			}
			return $this->create($user, $project, EnumProject::ROLE_INVITED, 0, $data['temp_role']);
		} catch(\Exception $e) {
			Log::error(Utils::getExceptionFullMessage($e));
			$this->validator->errors()->add("user_id",trans("custom_messages.unexpected_error"));
			return false;
		}
	}

	public function acceptInvitation(User $user, C3Project $project)
	{
		$this->validator = Validator::make([], [], []);	
		$userProject = UserProject::where("project_id", "=", $project->id)
							 	  ->where("user_id", "=", $user->id)
							 	  ->where("role", "=", EnumProject::ROLE_INVITED)
							 	  ->first();
		if ($userProject) {
			return $userProject->update(['role' => $userProject->temp_role, 'temp_role' => null]);
		} else {	
			$this->validator->errors()->add('id', 'Projeto não encontrado.');
			return false;
		}
	}

	public function denyInvitation(User $user, C3Project $project) 
	{
		$this->validator = Validator::make([], [], []);	
		$userProject = UserProject::where("project_id", "=", $project->id)
							 	  ->where("user_id", "=", $user->id)
							 	  ->where("role", "=", EnumProject::ROLE_INVITED)
							 	  ->first();
		if ($userProject) {
			return $userProject->delete();
		} else {	
			$this->validator->errors()->add('id', 'Projeto não encontrado.');
			return false;
		}
	}

	public function changeRole(C3Project $project, $data){
		$this->validator = Validator::make($data, $this->changeRoleValidation(), $this->messages());
		if ($this->validator->fails()) {
			return false;
		}
		$userProject = UserProject::where("project_id", "=", $project->id)
							 	  ->where("user_id", "=", $data['user_id'])
							 	  ->first();
		if ($userProject) {
			return $userProject->update(['role' => $data['role_id']]);
		} else {	
			$this->validator->errors()->add('id', 'O usuário não está vinculado ao projeto.');
			return false;
		}
	}

	public function boardAssigned(C3Project $project, $userId)
	{
		$this->validator = Validator::make([], [], []);	
		$userProject = UserProject::where("project_id", "=", $project->id)
						 	      ->where("user_id", "=", $userId)
						 	      ->first();
		if ($userProject) {
			return $userProject->update(['board_assigned' => 1]);
		} else {
			$this->validator->errors()->add('id', 'O usuário não está vinculado ao projeto.');
			return false;
		}

	}

	public function removeUser(C3Project $project, $userId){
		$userProject = UserProject::where('project_id', '=', $project->id)
								  ->where('user_id', '=', $userId)
								  ->first();
		if ($userProject) {
			return $userProject->delete();
		} else {
			$this->validator = Validator::make([],[]);
			$this->validator->errors()->add("user_id",trans("custom_messages.unexpected_error"));
			return false;
		}
	}

	public function getUsersToAssignBoard(C3Project $project)
	{
		$basequery = User::select(DB::raw('users.*'))
					     ->join('user_projects', 'users.id', '=', 'user_projects.user_id')
					     ->where('user_projects.board_assigned', '=', 0)
					     ->where('user_projects.role', '<>', EnumProject::ROLE_INVITED)
					     ->where('user_projects.project_id', '=', $project->id)
					     ->whereNotNull('users.trello_token');
		$user = Auth::user();
		if (empty($project->isOwner($user))) {
			$basequery->where('user_projects.user_id', '=', $user->id);
		}					
		return $basequery->get();
	}

	public function validation()
	{	
		return [
			'user_id' 	 => 'required',
			'project_id' => 'required',
			'temp_role'  => 'required',
		];
	}

	public function changeRoleValidation()
	{
		return [
			'user_id' 	 => 'required|exists:users,id',
			'role_id'		 => 'required'
		];
	}

	public function messages()
	{
		return [
			'user_id.required' 	  => 'Usuário não encontrado.',
			'user_id.exists' 	  => 'Usuário não encontrado.',
			'project_id.required' => 'Projeto não encontrado.',
			'temp_role.required'  => 'Selecione o perfil.',
			'role.required'  => 'Selecione o perfil.',
		];
	}

	public function getValidator()
	{
		return $this->validator;
	}
	
}