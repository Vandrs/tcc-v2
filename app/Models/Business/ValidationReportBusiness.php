<?php

namespace App\Models\Business;

use DB;
use App\Models\Enums\EnumLikert;

class ValidationReportBusiness
{
	public function getReport($projectValdiationId, $questionId = null, $gender = null, $minAge = null, $maxAge = null)
	{
		$baseQuery = $this->getBaseQuery();
		$baseQuery->where('pv.id', '=', $projectValdiationId);

		if ($questionId) {
			$baseQuery->where('q.id', '=', $questionId);
			$baseQuery->groupBy('q.id');
			$baseQuery->orderBy('q.id','ASC');
		}

		if ($gender) {
			$baseQuery->where('v.gender', '=', $gender);
		}	

		if ($minAge) {
			$baseQuery->where('v.age', '>=', $minAge);
		}

		if ($maxAge) {
			$baseQuery->where('v.age', '<=', $maxAge);
		}

		$result = [
			"data" 	 => $baseQuery->get(),
			"labels" => EnumLikert::getLabels()
		];

		return $result;
	}

	public function getBaseQuery()
	{
		$select = $this->getSelect();
		return DB::table('project_validations AS pv')
				 ->select($select)
		  		 ->join('validations AS v', 'pv.id', '=', 'v.project_validation_id')
		  		 ->join('answers AS a', 'v.id', '=', 'a.validation_id')
		  		 ->join('questions AS q', 'a.question_id', '=', 'q.id')
		  		 ->groupBy('a.option')
		  		 ->orderBy('a.option');
	}

	public function getSelect($questionId = null)
	{

		if ($questionId) {
			$select = [
				DB::raw('pv.id AS project_validation_id'),
				DB::raw('pv.title AS project_validation_title'),
				DB::raw('q.id AS question_id'),
				DB::raw('q.title AS question_title'),
				DB::raw('a.option'),
				DB::raw('count(a.option) AS answers')
			];
		} else {
			$select = [
				DB::raw('pv.id AS project_validation_id'),
				DB::raw('pv.title AS project_validation_title'),
				DB::raw('a.option'),
				DB::raw('count(a.option) AS answers')
			];
		}
 
		return $select;	
	}

	public function recommendReport($projectValdiationId, $gender = null, $minAge = null, $maxAge = null)
	{
		$query = DB::table('project_validations AS pv')
				   ->select([
				   		DB::raw('pv.id AS project_validation_id'),
						DB::raw('pv.title AS project_validation_title'),
				   		DB::raw('v.recommend AS question_id'),
				   		DB::raw('v.recommend AS "option"'),
				   		DB::raw('count(v.recommend) AS answers')
				   		
				   	])
				   ->join('validations AS v', 'pv.id', '=', 'v.project_validation_id')
		  		   ->where('pv.id', '=', $projectValdiationId)
		  		   ->groupBy('v.recommend');
		if ($gender) {
			$baseQuery->where('v.gender', '=', $gender);
		}	
		if ($minAge) {
			$baseQuery->where('v.age', '>=', $minAge);
		}
		if ($maxAge) {
			$baseQuery->where('v.age', '<=', $maxAge);
		}
		$result = [
			"data" 	 => $query->get(),
			"labels" => [0 => "Não", 1 => 'Sim']
		];
		return $result;
	}

}