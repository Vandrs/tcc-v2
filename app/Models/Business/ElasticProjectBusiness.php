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
		$data['url'] = $project->url;
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
		$data["followers"] = self::instance()->parseFollowers($project);
		$data["posts"] = self::instance()->parsePosts($project);
		$data["validations"] = self::instance()->parseValidations($project);
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

	private function parseFollowers(Project $project){
		$followers = [];
		$project->getFollowers()->each(function($user) use (&$followers){
			array_push($followers,[
				"id" => $user->id,
				"name" => $user->name,
				"email" => $user->email,
				"role" => $user->role
			]);
			return $followers;
		});
		return $followers;
	}

	private function parsePosts(Project $project){
		$posts = [];
		$project->getPosts()->each(function($post) use(&$posts){
			array_push($posts,[
				"id"    	 => $post->id,
				"title" 	 => $post->title,
				"text"  	 => $post->text,
				"created_at" => $post->created_at->format('Y-m-d H:i:s'),
				"updated_at" => $post->created_at->format('Y-m-d H:i:s'),
				"createUser" => [
					"id" 	=> $post->createUser->id,
					"name"  => $post->createUser->name
				]
			]);
		});
		return $posts;
	}

	private function parseValidations(Project $project)
	{
		$validations = [];
		$project->validations->each(function($validation) use (&$validations){
			array_push($validations, [
				"id" 		 => $validation->id,
				"title" 	 => $validation->title,
				"url" 		 => $validation->url,
				"started_at" => $validation->started_at->format('Y-m-d H:i:s'),
				"ended_at"   => $validation->ended_at->format('Y-m-d H:i:s'),
			]);
		});	
		return $validations;
	}

}