<?php 

namespace App\Models\Business;

use DB;
use Log;
use App\Utils\Utils;
use App\Models\DB\DiffMatrix;
use App\Models\DB\Project;
use App\Models\DB\User;
use App\Models\DB\ProjectNote;

class DiffMatrixBusiness{
	private $output;
	private $progressBar;

	public function __construct($output = null){
		$this->output = $output;
	}

	public function makeMatrix(){
		try {
			DB::beginTransaction();
			$this->truncateTable();
			$projects = Project::all();
			$totalProjects = $projects->count();
			$totalDiffs = ($totalProjects * $totalProjects) - $totalProjects;
			$this->createBar($totalDiffs);
			$mDiff = $this;
			$projects->each(function($projectA) use($projects,$mDiff){
				$projects->each(function($projectB) use($projectA,$mDiff){
					if($projectA->id != $projectB->id){
						$diff = $mDiff->calcDiff($projectA, $projectB);
						DiffMatrix::create(
							['project_a' => $projectA->id, 
							 'project_b' => $projectB->id,
							 'diff' 	 => $diff]);
					}
				});
			});
			DB::commit();
			$this->finishBar();
		} catch (\Exception $e) {
			DB::rollback();
			Log::error(Utils::getExceptionFullMessage($e));
			throw new Exception("Erro ao calcular matriz de diferênças");
		}
	}

	public function calcDiff($projectA, $projectB){
		$this->advanceBar();
		$diffs = [];
		$notes = $projectA->notes;
		$notes->each(function($noteA) use($projectB, &$diffs) {
			$query = ProjectNote::where('user_id','=',$noteA->user_id)->where('project_id','=',$projectB->id);
			if($noteB = $query->first()){
				array_push($diffs,($noteA->note - $noteB->note));
			}
		});
		if(!empty($diffs)){
			return array_sum($diffs) / count($diffs);
		}
		return 0;
	}

	private function truncateTable(){
		DB::statement("TRUNCATE TABLE diff_matrix");
	}

	public function createBar($qtd){
		if($this->output){
			$this->progressBar = $this->output->createProgressBar($qtd);
		}
	}

	public function advanceBar($qtd = 1){
		if($this->progressBar){
			$this->progressBar->advance($qtd);
		}
	}

	public function finishBar(){
		if($this->progressBar){
			$this->progressBar->finish();
		}
	}
}