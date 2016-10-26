<?php

namespace App\Models\Business;

use App\Models\DB\Project;
use App\Models\DB\User;
use App\Models\Business\UserProjectBusiness;
use App\Models\Business\CrudProjectBusiness;
use App\Models\Interfaces\C3Project;
use App\Models\Enums\EnumProject;

class ProjectFollowerBusiness{

	private static $instance;

	private function getInstance(){
		if(empty(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}

	public static function follow(User $user, C3Project $project){
		$userProjectBuiness = new UserProjectBusiness();
		$userProject = $userProjectBuiness->create($user, $project, EnumProject::ROLE_FOLLOWER);
		CrudProjectBusiness:dispathElasticJob($project);
		return $userProject;
	}

	public static function unfollow(User $user, C3Project $project){
		$userProjectBuiness = new UserProjectBusiness();
		$deleteResponse = $userProjectBuiness->delete($user, $project);
		CrudProjectBusiness:dispathElasticJob($project);
		return $deleteResponse;
	}

	public static function isUserFollowingProject(User $user, C3Project $project){
		return $project->getFollowers()->where('id',$user->id,false)->first();
	}
}