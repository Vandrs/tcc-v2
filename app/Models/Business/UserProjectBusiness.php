<?php 

namespace App\Models\Business;

use App\Models\DB\User;
use App\Models\DB\UserProject;
use App\Models\Business\CrudProjectBusiness;
use App\Models\Utils\Utils;
use App\Models\Interfaces\C3Project;
use Log;

class UserProjectBusiness{

	public function create(User $user, C3Project $project, $role){
		return UserProject::create([
			'user_id'	 => $user->id,
			'project_id' => $project->id,
			'role' 		 => $role
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
	
}