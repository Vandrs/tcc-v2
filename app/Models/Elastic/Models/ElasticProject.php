<?php 

namespace App\Models\Elastic\Models;

use App\Models\Elastic\Models\ElasticModel;
use App\Utils\DateUtil;

class ElasticProject extends ElasticModel{
	private $type = 'project';
	Private $mappingFile = 'project.json';

	protected $fillable = ['id', 'title', 'category_id','description', 'category','avg_note', 'total_notes', 'created_at', 'updated_at'];


	public function __construct($attributes = null){
		parent::__construct($attributes, $this->type, $this->mappingFile);
	}

	public function getCreatedAtAttribute($value){
		if($value){
			return DateUtil::dateTimeInBrazil($value);
		}
		return null;
	}

	public function getCategoryAttribute($value){
		if(is_array($value)){
			return (object) $value;
		}
		return $value;
	}

	public function getUpdatedAtAttribute($value){
		if($value){
			return DateUtil::dateTimeInBrazil($value);
		}
		return null;
	}

	public function getAvgNoteAttribute($value){
		if($value){
			return number_format($value,'2',',','.');
		}
		return null;
	}
}