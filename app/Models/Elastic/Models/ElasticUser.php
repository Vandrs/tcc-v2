<?php 

namespace App\Models\Elastic\Models;

use App\Models\Elastic\Models\ElasticModel;

class ElasticUser extends ElasticModel {

	private $type = 'user';
	private $mappingFile = 'user.json';

	protected $fillable = [];

	public function __construct($attributes = null){
		parent::__construct($attributes, $this->type, $this->mappingFile);
	}

	protected function transform() {

	}

}

