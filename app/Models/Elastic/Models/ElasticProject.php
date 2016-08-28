<?php 

namespace App\Models\Elastic\Models;

use App\Models\Elastic\Models\ElasticModel;
use App\Models\Elastic\Dummies\Image;
use App\Utils\DateUtil;
use Elastica\Exception\NotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use App\Models\Interfaces\C3Project;
use App\Models\Elastic\ElasticSearch;

class ElasticProject extends ElasticModel implements C3Project{
	
	private $type = 'project';
	Private $mappingFile = 'project.json';

	protected $fillable = ['id', 'title', 'category_id','description', 'category', 'urls', 'avg_note', 'total_notes', 'images', 'files', 'members', 'followers', 'posts', 'created_at', 'updated_at'];

	public function __construct($attributes = null){
		parent::__construct($attributes, $this->type, $this->mappingFile);
	}

	protected function transform(){
		$category = $this->category;
		if(!empty($category) && !is_object($category)){
			$this->category = (object)$category;
		}
		$createdAt = $this->created_at;
		if(!empty($createdAt) && !is_object($createdAt)){
			$this->created_at = DateUtil::dateTimeInBrazil($createdAt);
		}
		$updatedAt = $this->updated_at;
		if(!empty($updatedAt) && !is_object($updatedAt)){
			$this->updated_at = DateUtil::dateTimeInBrazil($updatedAt);
		}
		$images = $this->images;
		if(!empty($images)){
			$arrImages = [];
			foreach($images as $jsonImage){
				array_push($arrImages, new Image($jsonImage));
			}
			$this->images = new Collection($arrImages);
		} else {
			$this->images = new Collection();
		}

		$files = $this->files;
		if(!empty($files)){
			$arrFiles = [];
			foreach($files as $file){
				array_push($arrFiles, (Object) $file);
			}
			$this->files = new Collection($arrFiles);
		} else {
			$this->files = new Collection();
		}

		$members = $this->members;
		$this->members = new Collection();
		if(!empty($members)){
			foreach($members as $key => $member){
				$this->members->put($key, (object) $member);
			}
		}

		$followers = $this->followers;
		$this->followers = new Collection();
		if(!empty($followers)){
			foreach($followers as $key => $follower){
				$this->followers->put($key, (object) $follower);
			}
		}

		$posts = $this->posts;
		$this->posts = new Collection();
		if(!empty($posts)){
			foreach($posts as $key => $post){
				$this->posts->put($key, (object)$post);
			}
		}

	}

	public function imageCoverOrFirst(){
		foreach($this->images->all() as $image){
			if($image->cover == 1){

				return $image;
			}
		}
		return $this->images->first();
	}

	public function getMembers(){
		return $this->members;
	}

	public function getFollowers(){
		return $this->followers;
	}

	public function getPosts(){
		return $this->posts;
	}

	public function isMember($user){
		return $this->getMembers()->where('id', $user->id, false);
	}

}