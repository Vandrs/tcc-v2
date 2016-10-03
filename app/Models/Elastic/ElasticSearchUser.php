<?php

namespace App\Models\Elastic;

use Auth;
use Log;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Utils\Utils;
use App\Models\Elastic\ElasticSearch;
use App\Models\Elastic\Models\ElasticUser;
use Elastica\Search;
use Elastica\Query;
use Elastica\Query\MatchAll;
use Elastica\Query\MultiMatch;
use Elastica\QueryBuilder;
use Elastica\ResultSet;

class ElasticSearchUser
{
	private $search;
	private $query;

	public function __construct()
	{
		$elasticsearch = new ElasticSearch();
		$elasticUser = new ElasticUser();
		$this->search = new Search($elasticsearch->getElasticClient());
		$this->search->addIndex($elasticsearch->getElasticIndex());
		$this->search->addType($elasticUser->getType());
	}	

	public function searchUsers($terms, $size = 9, $page = 1)
	{
		$query = new Query();
		$queryBuilder = new QueryBuilder;
		$boolQuery = $queryBuilder->query()->bool();

		if (isset($terms["name"]) && !empty($terms["name"])) {
			$boolQuery->addShould($queryBuilder->query()->match('name', $terms["name"]));
		}

		if (isset($terms["skills"]) && !empty($terms["skills"])) {
			if (!is_array($terms["skills"])) {
				$terms["skills"] = $this->parseSkills($terms["skills"]);
			}
			$boolSkills = $queryBuilder->query()->bool();
			foreach($terms["skills"] as $skill){
				$boolSkills->addShould($queryBuilder->query()->match('skills', $skill));
			}	
			$boolQuery->addShould($boolSkills);
		}

		if (isset($terms["work"]) && !empty($terms["work"])) {
			$workFields = ["works.title","works.description","works.company"]; 
			$boolQuery->addShould($this->buildShouldTerms($queryBuilder, $workFields, $terms["work"]));		
		}

		if (isset($terms["graduation"]) && !empty($terms["graduation"])) {
			$graduationFields = ["graduations.course","graduations.institution"]; 
			$boolQuery->addShould($this->buildShouldTerms($queryBuilder, $graduationFields, $terms["graduation"]));		
		}

		if(Auth::check()){
			$boolQuery->addMustNot($queryBuilder->query()->term(["id" => Auth::user()->id]));
		}

		$query->setQuery($boolQuery)
			  ->setFrom(Utils::calcFromIndex($page,$size))
    		  ->setSize($size);

		return $this->doSearch($query,$page,$size);		
	}

	private function doSearch(Query $query, $page, $size)
	{
		try{
			$this->search->setQuery($query);
			$result = $this->search->search();
			return $this->makePaginator($result, $page, $size);	
		}catch(\Exception $e){
			Log::error(Utils::getExceptionFullMessage($e));
			return new Collection();
		}
	}

	private function makePaginator(ResultSet $result, $page, $size)
	{
		$users = $this->parseUsers($result->getResults());
		$paginator = new LengthAwarePaginator(
							$users,
							$result->getTotalHits(),
							$size,
							$page
						 );
		return $paginator;
	}

	private function parseUsers($items)
	{
		$users = [];
		foreach($items as $item){
			$user = new ElasticUser($item->getDocument()->getData());
			array_push($users, $user);
		}
		return new Collection($users);
	}

	private function buildShouldTerms(QueryBuilder $queryBuilder, $fields, $term)
	{
		$boolQuery = $queryBuilder->query()->bool();
		foreach ($fields as $field) {
			$boolQuery->addShould($queryBuilder->query()->match($field, $term));
		}
		return $boolQuery;
	}

	private function parseSkills($skills)
	{	
		$skills = explode(",", $skills);
		$data = [];
		foreach ($skills as $skill) {
			array_push($data,trim($skill));
		}
		return $data;
	}
}