<?php 

namespace App\Models\Business;

use App\Utils\ProgressBarTrait;
use App\Utils\Utils;
use App\Models\DB\Project;
use App\Models\DB\User;
use App\Models\Enums\EnumProject;
use App\Models\Elastic\ElasticSearch;
use App\Models\Elastic\Models\ElasticProject;
use App\Models\Elastic\Models\ElasticUser;
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
				$elasticDocument = new Document($project->id, ElasticProjectBusiness::getElasticProjectData($project));
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
		$this->setActive('projects', $excludeIds);
		$this->finishBar();
	}

	public function exportUsers(){
		$qtdItems = $this->getQtdUsersToExport();
		$this->createProgressBar($qtdItems);
		$excludeIds = [];
		$elasticExportBusiness = $this;
		while($qtdItems){
			$exportDocuments = [];
			$users = $this->getUsersToExport($excludeIds, $this->limit);
			$users->each(function($user) use($elasticExportBusiness, &$excludeIds, &$exportDocuments){
				$elasticDocument = new Document($user->id, ElasticUserBusiness::getElasticUserData($user));
				array_push($exportDocuments,$elasticDocument);
				array_push($excludeIds,$user->id);
				$elasticExportBusiness->advanceBar();
			});
			try{
				$elasticSearch = new ElasticSearch;
				$elasticSearch->bulkModelsToElastic(new ElasticUser, $exportDocuments);
			} catch(\Exception $e){
				Log::error(Utils::getExceptionFullMessage($e));
			}
			$qtdItems = $this->getQtdUsersToExport($excludeIds);
		}
		$this->setActive('users', $excludeIds);
		$this->finishBar();
	}

	public function exportProject(Project $project){
		$elasticDocument = new Document($project->id, ElasticProjectBusiness::getElasticProjectData($project));
		$elasticSearch = new ElasticSearch;
		$elasticSearch->bulkModelsToElastic(new ElasticProject, [$elasticDocument]);
		$this->setActive('projects', [$project->id]);
	}

	public function exportUser(User $user){
		$elasticDocument = new Document($user->id, ElasticUserBusiness::getElasticUserData($user));
		$elasticSearch = new ElasticSearch;
		$elasticSearch->bulkModelsToElastic(new ElasticUser, [$elasticDocument]);
		$this->setActive('users', [$user->id]);				
	}

	private function setActive($table, $ids){
		DB::table($table)
			->whereIn('id', $ids)
			->update(['in_elastic' => EnumProject::STATUS_ACTIVE]);
	}

	private function getProjectsToExport($excludeIds = [], $limit = 1000){
		return $this->getBaseQueryProjects($excludeIds)->take($limit)->get();
	}

	private function getQtdProjectsToExport($excludeIds = []){
		return $this->getBaseQueryProjects($excludeIds)->count();
	}

	private function getBaseQueryProjects($excludeIds = []){
		return Project::where('in_elastic', '=', EnumProject::STATUS_INACTIVE)
			   		  ->whereNotIn('id',$excludeIds);
	}

	private function getUsersToExport($excludeIds = [], $limit = 1000){
		return $this->getBaseQueryUsers($excludeIds)->take($limit)->get();
	}

	private function getQtdUsersToExport($excludeIds = []){
		return $this->getBaseQueryUsers($excludeIds)->count();	
	}

	private function getBaseQueryUsers($excludeIds = []){
		return User::where('in_elastic', '=', EnumProject::STATUS_INACTIVE)
				   ->whereNotIn('id',$excludeIds );
	}

}