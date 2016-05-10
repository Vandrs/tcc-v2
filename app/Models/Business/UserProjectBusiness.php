<?php 

namespace App\Models\Business;

use App\Models\DB\User;
use App\Models\DB\Project;
use App\Models\DB\UserProject;

class UserProjectBusiness{
	public function create(User $user, Project $project, $role){
		return UserProject::create([
			'user_id'	 => $user->id,
			'project_id' => $project->id,
			'role' 		 => $role
		]);
	}
}