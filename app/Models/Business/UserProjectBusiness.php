<?php 

namespace App\Models\Business;

use App\Models\DB\User;
use App\Models\DB\UserProject;
use App\Models\Business\CrudProjectBusiness;
use App\Models\Utils\Utils;
use App\Models\Interfaces\C3Project;
use Log;
use Validator;

class UserProjectBusiness
{

	const CREATOR = 1;	
	const NON_CREATOR = 0;

	private $validator;

	public function create(User $user, C3Project $project, $role, $creator = 0)
	{
		return UserProject::create([
			'user_id'	 => $user->id,
			'project_id' => $project->id,
			'role' 		 => $role,
			'creator'	 => $creator
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

	public function invite(User $user, C3Project $project, $role, $creator = 0)
	{
		$this->validator = Validator::make([],[]);
		$exists = UserProject::where("project_id","=",$project->id)
							 ->where("user_id","=",$user->id)
							 ->exists();
		if($exists){
			$this->validator->errors()->add("user_id","Usuário já pertence ao projeto.");
			return false;
		}
		return $this->create();
	}

	public function getValidator()
	{
		return $this->validator;
	}
	
}