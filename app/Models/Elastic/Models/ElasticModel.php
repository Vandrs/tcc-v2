<?php 

namespace App\Models\Elastic\Models;

use App\Utils\Model;
use Storage;

abstract class ElasticModel extends Model{

	private $type;
	private $mappingPath = '/mapping/';
	private $mappingFile;
	private $mapping;
	private $localDisk = 'elasticsearch';

	public function __construct($attributes = null, $type, $mappingFile){
		parent::__construct($attributes);
		$this->type = $type;
		$this->mappingFile = $mappingFile;
	}

	public function getMapping(){
		if(empty($this->mapping)){
			$this->getMappingFromFile();
		}
		return $this->mapping;
	}

	public function getType(){
		return $this->type;
	}

	private function getMappingFromFile(){
		$disk = Storage::disk($this->localDisk);
		if($disk->exists($this->mappingPath.$this->mappingFile)){
			$content = $disk->get($this->mappingPath.$this->mappingFile);
			$this->mapping = json_decode($content, true);
		}
	}
}
