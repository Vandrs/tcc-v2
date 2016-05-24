<?php 

namespace App\Models\Business;

use App\Models\DB\Project;
use App\Models\DB\DiffMatrix;
use Illuminate\Support\Collection;

class SlopeOne{

	public function getPredictions($user){
		$ratedNotes = $user->notes;
		if($ratedNotes->count() == 0){
			return new Collection;
		}
		$notRated = $this->getUserNotRatedProjects($user);
		$notRated->each(function($project) use($ratedNotes){
			$diffs = [];
			$ratedNotes->each(function($ratedNote) use($project, &$diffs){
				$diffNotes = DiffMatrix::where('project_a','=',$ratedNote->project_id)
						  		  	   ->where('project_b','=',$project->id)
						  		  	   ->first();
				if($diffNotes){
					array_push($diffs,($ratedNote->note + $diffNotes->diff));	
				}
			});
			if(count($diffs)){
				$project->preference = array_sum($diffs) / count($diffs);	
			} else {
				$project->preference = 0;
			}
			
		});
		$sortFunction = function($a, $b){
			if($a->preference == $b->preference){
				return 0;
			}
			return $a->preference < $b->preference ? 1 : -1;
		};
		return $notRated->sort($sortFunction)->values();
	} 

	public function getUserNotRatedProjects($user){
		$excludeIds = $user->notes->pluck('project_id')->all();
		$projectsAsOwner = $user->projectsAsOwner()->pluck('id')->all();
		$excludeIds = array_merge($excludeIds,$projectsAsOwner);
		return Project::whereNotIn('id',$excludeIds)->get();
	}
}