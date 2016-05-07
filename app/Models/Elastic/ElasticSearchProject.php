<?php 

namespace App\Models\Elastic;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Elastic\ElasticSearch;
use App\Models\Elastic\Models\ElasticProject;
use App\Utils\Utils;
use Elastica\Search;
use Elastica\Query;
use Elastica\Query\MatchAll;
use Elastica\Query\MultiMatch;
use Elastica\QueryBuilder;
use Elastica\ResultSet;
use Log;

class ElasticSearchProject{

	private $search;
	private $query;

	public function __construct(){
		$elasticsearch = new ElasticSearch();
		$elasticProject = new ElasticProject();
		$this->search = new Search($elasticsearch->getElasticClient());
		$this->search->addIndex($elasticsearch->getElasticIndex());
		$this->search->addType($elasticProject->getType());
	}

	public function getTopRatedProjects($size = 9, $page = 1){
		$query = new Query();
		$query->setFrom(Utils::calcFromIndex($page,$size))
    		  ->setSize($size)
    		  ->setSort([
    		  		'avg_note' 	  => 'desc',
    		  		'total_notes' => 'desc'
    		  	]);
    	$query->setQuery(new MatchAll());
    	return $this->doSearch($query,$page,$size);
	}

	public function searchProject($term, $filters = [], $order = [], $size = 9, $page = 1){
		$query = new Query();
		$queryBuilder = new QueryBuilder;
		$boolQuery = $queryBuilder->query()->bool();
		if($term){
			$boolQuery->addMust($this->simpleMultiMatchQuery($term));	
		} else {
			$boolQuery->addMust(new MatchAll());	
		}
		
		if(!empty($filters)){
			$boolQuery->addFilter($queryBuilder->query()->term($filters));
		}
		$query->setQuery($boolQuery)
			  ->setFrom(Utils::calcFromIndex($page,$size))
    		  ->setSize($size);
    	if(!empty($order)){
    		$query->setSort($order);
    	}
    	return $this->doSearch($query, $page, $size);
	}

	private function simpleMultiMatchQuery($term){
		$multiMatchQuery = new MultiMatch();
		$multiMatchQuery->setQuery($term)
						->setFields(['title','description','category.name'])
						->setType(MultiMatch::TYPE_BEST_FIELDS);
		return $multiMatchQuery;
	}

	private function doSearch(Query $query,$page,$size){
		try{
			$this->search->setQuery($query);
			$result = $this->search->search();
			return $this->makePaginator($result,$page,$size);	
		}catch(\Exception $e){
			dd(Utils::getExceptionFullMessage($e));
			Log::error(Utils::getExceptionFullMessage($e));
			return null;
		}
	}

	private function makePaginator(ResultSet $result, $page, $size){
		$projects = $this->parseProjects($result->getResults());
		$paginator = new LengthAwarePaginator(
							$projects,
							$result->getTotalHits(),
							$size,
							$page
						 );
		return  $paginator;
	}

	public function parseProjects($items){
		$projects = [];
		foreach($items as $item){
			array_push($projects,new ElasticProject($item->getDocument()->getData()));
		}
		return $projects;
	}
}