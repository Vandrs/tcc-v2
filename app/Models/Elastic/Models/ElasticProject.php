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

	protected $fillable = ['id', 'title', 'category_id','description', 'category', 'urls', 'avg_note', 'total_notes', 'images', 'files', 'members', 'created_at', 'updated_at'];

	public function __construct($attributes = null){
		parent::__construct($attributes, $this->type, $this->mappingFile);
	}

	public static function findById($id){
		$model = new self();
		$elasticSearch = new ElasticSearch;
		$index = $elasticSearch->getElasticIndex();
		$type = $index->getType($model->type);
		try{
			$document = $type->getDocument($id);
			return new static($document->getData());
		} catch(NotFoundException $e){
			throw new ModelNotFoundException;
		}
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
		if(!empty($members)){
			$this->members = new Collection();
			foreach($members as $key => $member){
				$this->members->put($key, (object) $member);
			}
		} else {
			$this->members = new Collection();
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

	public function getMembers()
	{
		return $this->members;
	}

}