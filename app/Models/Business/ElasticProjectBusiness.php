<?php

namespace App\Models\Business;
use App\Models\DB\Project;
use App\Models\DB\ProjectNote;

class ElasticProjectBusiness{

	public static function instance(){
		return new self;
	}

	public static function getElasticProjectData(Project $project){
		$data = $project->getAttributes();
		self::instance()->excludeExportFields($data);
		$data["avg_note"] = $project->getAvgNote();
		$data["total_notes"] = $project->getTotalNotes();
		return $data;
	}

	private function getExcludeExportFields(){
		return ['in_elastic'];
	}

	private function excludeExportFields(&$data){
		foreach($this->getExcludeExportFields() as $field){
			if(isset($data[$field])){
				unset($data[$field]);
			}
		}
	}

}