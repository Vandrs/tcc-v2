<?php 

namespace App\Models\Elastic\Models;

use App\Models\Elastic\Models\ElasticModel;
use App\Models\Elastic\Dummies\Image;
use App\Utils\DateUtil;
use Illuminate\Support\Collection;

class ElasticProject extends ElasticModel{
	
	private $type = 'project';
	Private $mappingFile = 'project.json';

	protected $fillable = ['id', 'title', 'category_id','description', 'category','avg_note', 'total_notes', 'images', 'created_at', 'updated_at'];

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
	}

	public function getAvgNoteAttribute($value){
		if($value){
			return number_format($value,'2',',','.');
		}
		return null;
	}

	public function imageCoverOrFirst(){
		$image = $this->images->where("cover",1,false)->first();
		if($image){
			return $image;
		} else {
			return $this->images->first();
		}
	}

}