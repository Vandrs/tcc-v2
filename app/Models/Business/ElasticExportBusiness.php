<?php 

namespace App\Models\Business;

use App\Utils\ProgressBarTrait;
use App\Utils\Utils;
use App\Models\DB\Project;
use App\Models\Elastic\ElasticSearch;
use App\Models\Elastic\ElasticProject;
use App\Models\Business\ElasticProjectBusiness;
use Elastica\Document;
use Log;
use DB;

class ElasticExportBusiness{

	use ProgressBarTrait;
	private $limit = 1000;

	public function exportProjects(){
		$qtdItems = $this->getQtdProjectsToExport();
		$this->createProgressBar($qtdItems);
		$excludeIds = [];
		$elasticExportBusiness = $this;
		while($qtdItems){
			$exportDocuments = [];
			$projects = $this->getProjectsToExport($excludeIds,$this->limit);
			$projects->each(function($project) use($elasticExportBusiness, &$excludeIds, &$exportDocuments){
				$elasticDocument = new Document($project->id,ElasticProjectBusiness::getElasticProjectData($project));
				array_push($exportDocuments,$elasticDocument);
				array_push($excludeIds,$project->id);
				$elasticExportBusiness->advanceBar();
			});
			try{
				$elasticSearch = new ElasticSearch;
				$elasticSearch->bulkModelsToElastic(new ElasticProject, $exportDocuments);
			} catch(\Exception $e){
				Log::error(Utils::getExceptionFullMessage($e));
			}
			$qtdItems = $this->getQtdProjectsToExport($excludeIds);
		}
		DB::table('projects')
		  ->whereIn('id',$excludeIds)
		  ->update(['in_elastic' => Project::ACTIVE]);
		$this->finishBar();
	}

	private function getProjectsToExport($excludeIds = []){
		return $this->getBaseQueryProjects($excludeIds)->get();
	}

	private function getQtdProjectsToExport($excludeIds = []){
		return $this->getBaseQueryProjects($excludeIds)->count();
	}

	private function getBaseQueryProjects($excludeIds = []){
		return Project::where('in_elastic', '=', Project::INACTIVE)
			   		  ->whereNotIn('id',$excludeIds);
	}

}