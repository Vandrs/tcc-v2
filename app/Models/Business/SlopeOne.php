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
				array_push($diffs,($ratedNote->note + $diffNotes->diff));
			});
			$project->preference = array_sum($diffs) / count($diffs);
		});
		return $notRated->sort(function($a,$b){
			if($a->preference == $b->preference){
				return 0;
			}
			return $a->preference < $b->preference ? 1 : -1;
		});
	} 

	public function getUserNotRatedProjects($user){
		$excludeIds = [];
		$user->notes->each(function($note) use(&$excludeIds){
			array_push($excludeIds,$note->project_id);
		});
		return Project::whereNotIn('id',$excludeIds)->get();		
	}
}