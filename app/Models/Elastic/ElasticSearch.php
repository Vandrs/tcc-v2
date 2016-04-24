<?php 

namespace App\Models\Elastic;

use Elastica\Client;
use Elastica\Index;
use Config;
use App\Models\Elastic\ElasticProject;
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

	public function mapAllTypes(){
		$elasticIndex = new Index($this->client,$this->index);
		foreach($this->getModels() as $modelClass){
			$elasticModel = new $modelClass;
			$type = $elasticIndex->getType($elasticModel->getType());
			$mapping = new Mapping($type,$elasticModel->getMapping());
			$mapping->send();
		}
	}

	private function getModels(){
		return [
			ElasticProject::class
		];
	} 
}	