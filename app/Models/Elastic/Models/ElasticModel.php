<?php 

namespace App\Models\Elastic\Models;

use App\Utils\Model;
use App\Models\Elastic\ElasticSearch;
use Storage;

abstract class ElasticModel extends Model
{

	private $type;
	private $mappingPath = '/mapping/';
	private $mappingFile;
	private $mapping;
	private $localDisk = 'elasticsearch';
	protected $primaryKey = 'id';

	public function __construct($attributes = null, $type, $mappingFile)
	{
		parent::__construct($attributes);
		$this->type = $type;
		$this->mappingFile = $mappingFile;
		if ($attributes) {
			$this->transform();
		}
	}

	protected function getTypeObject()
	{
		$elasticSearch = new ElasticSearch;
		$index = $elasticSearch->getElasticIndex();
		return $index->getType($this->type);
	}

	public static function findById($id)
	{
		try {
			$model = new static();
			$type = $model->getTypeObject();
			$document = $type->getDocument($id);
			return new static($document->getData());
		} catch (NotFoundException $e) {
			throw new ModelNotFoundException;
		}
	}

	public function delete(){
		$model = new static();
		$type = $model->getTypeObject();
		$type->deleteById($this->$this->getPK());
	}

	public static function deleteById($id){
		$model = new static();
		$type = $model->getTypeObject();
		$type->deleteById($id);
        $type->getIndex()->refresh();
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

	private function getPK(){
		return $this->primaryKey;
	}

	abstract protected function transform();

}
