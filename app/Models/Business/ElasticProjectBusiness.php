<?php

namespace App\Models\Business;
use App\Models\DB\Project;

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
		$data["urls"] = self::instance()->parseUrls($project);
		$data["files"] = self::instance()->parseFiles($project);
		$data["members"] = self::instance()->parseMembers($project);
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

	private function parseFiles(Project $project){
		$files = [];
		$project->files->each(function($file) use(&$files){
			array_push($files,[
				"id" => $file->id,
				"title" => $file->title,
				"file" => $file->file,
				"url" => $file->url
			]);
		});
		return $files;
	}

	private function parseUrls(Project $project){
		$urls = [];
		$projectUrls = $project->urls;
		if(!empty($projectUrls)){
			$urls = $projectUrls;
		}
		return $urls;
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

	private function parseMembers(Project $project){
		$members = [];
		$project->getMembers()->each(function($user) use (&$members){
			array_push($members,[
				"id" => $user->id,
				"name" => $user->name,
				"email" => $user->email,
				"role" => $user->role
			]);
		});
		return $members;
	}

}