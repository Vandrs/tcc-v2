<?php 

namespace App\Models\Elastic;

use Elastica\Client;
use Elastica\Index;
use Config;
use App\Models\Elastic\ElasticProject;
use App\Models\Elastic\ElasticModel;
use Elastica\Type\Mapping;


class ElasticSearch{
	private $client;
	private $index;

	public function __construct(){
		$this->client = new Client([
			'host' 	  => Config::get('elasticsearch.host'),
			'port' 	  => Config::get('elasticsearch.port'),
			'timeout' => 30
		]);
		$this->index = Config::get('elasticsearch.index');
	}

	public function bulkModelsToElastic(ElasticModel $elasticModel, $documents){
		$elasticIndex = $this->getElasticIndex();
		$elasticType = $elasticIndex->getType($elasticModel->getType());
		$elasticType->addDocuments($documents);
		$elasticType->getIndex()->refresh();
	}

	public function mapAllTypes(){
		$elasticIndex = $this->getElasticIndex();
		foreach($this->getModels() as $modelClass){
			$elasticModel = new $modelClass;
			$type = $elasticIndex->getType($elasticModel->getType());
			$modelMapping = $elasticModel->getMapping();
			if(!empty($type) && !empty($modelMapping)){
				$mapping = new Mapping($type,$modelMapping);
				$mapping->send();
			}
		}
	}

	private function getModels(){
		return [
			ElasticProject::class
		];
	}

	private function getElasticIndex(){
		return new Index($this->client,$this->index);
	}

}	