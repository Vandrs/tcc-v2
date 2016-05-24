<?php 


namespace App\Models\Business;
use App\Models\DB\User;
use App\Models\Business\SlopeOne;
use App\Models\Elastic\ElasticSearchProject;

class ProjectBusiness{
	public function getFeaturedProjects(User $user = null){
		$qtdProjects = 9;
		if($user && $user->qtdEvaluatedProjects()){
			$slopeOne = new SlopeOne;
			$projects = $slopeOne->getPredictions($user);
			return $projects->slice(0,$qtdProjects);
		} else {
			$searchProject = new ElasticSearchProject;
    		return $searchProject->getTopRatedProjects($qtdProjects);
		}
	}

	public function getFeaturedProjectsForUser(User $user, $qtdProjects = 4){
		if($user && $user->qtdEvaluatedProjects()){
			$slopeOne = new SlopeOne;
			$projects = $slopeOne->getPredictions($user);
			return $projects->slice(0,$qtdProjects);
		} else {
			$excludeIds = $user->projectsAsOwner()->pluck('id');
			
		}
	}
}