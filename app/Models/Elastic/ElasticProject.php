<?php 

namespace App\Models\Elastic;

use App\Models\Elastic\ElasticModel;

class ElasticProject extends ElasticModel{
	private $type = 'project';
	Private $mappingFile = 'project.json';

	public function __construct($attributes = null){
		parent::__construct($attributes, $this->type, $this->mappingFile);
	}
}