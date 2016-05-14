<?php

namespace App\Models\Business;
use App\Models\DB\Project;
use App\Models\DB\ProjectNote;

class ElasticProjectBusiness{

	private static $instance;

	public static function instance(){
		if(empty(self::$instance)){
			self::$instance = new self;
		}
		return self::$instance;
	}

	public static function getElasticProjectData(Project $project){
		$data = $project->getAttributes();
		self::instance()->excludeExportFields($data);
		$data["avg_note"] = $project->getAvgNote();
		$data["total_notes"] = $project->getTotalNotes();
		$data["category"] = [
			"id"   => $project->category->id,
			"name" => $project->category->name
		];
		$data["images"] = self::instance()->parseImages($project);
		return $data;
	}

	private function parseImages(Project $project){
		$images = [];
		$project->images->each(function($image) use(&$images){
			array_push($images, [
				"id" 	=> $image->id,
				"title" => $image->title,
				"file" 	=> $image->file,
				"cover" => $image->cover
			]);
		});
		return $images;
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