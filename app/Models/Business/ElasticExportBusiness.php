<?php 

namespace App\Models\Business;

use App\Utils\ProgressBarTrait;
use App\Utils\Utils;
use App\Models\DB\Project;
use App\Models\Enums\EnumProject;
use App\Models\Elastic\ElasticSearch;
use App\Models\Elastic\Models\ElasticProject;
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
		$this->setActive($excludeIds);
		$this->finishBar();
	}

	public function exportProject(Project $project){
		$elasticDocument = new Document($project->id,ElasticProjectBusiness::getElasticProjectData($project));
		$elasticSearch = new ElasticSearch;
		$elasticSearch->bulkModelsToElastic(new ElasticProject, [$elasticDocument]);
		$this->setActive([$project->id]);
	}

	private function setActive($ids){
		DB::table('projects')
			->whereIn('id', $ids)
			->update(['in_elastic' => EnumProject::STATUS_ACTIVE]);
	}

	private function getProjectsToExport($excludeIds = []){
		return $this->getBaseQueryProjects($excludeIds)->get();
	}

	private function getQtdProjectsToExport($excludeIds = []){
		return $this->getBaseQueryProjects($excludeIds)->count();
	}

	private function getBaseQueryProjects($excludeIds = []){
		return Project::where('in_elastic', '=', EnumProject::STATUS_INACTIVE)
			   		  ->whereNotIn('id',$excludeIds);
	}

}