<?php 

namespace App\Models\Business;

use Validator;
use App\Models\DB\Question;

class QuestionBusiness
{
	private $validator;

	public function create($data)
	{
		$this->validator = Validator::make($data, $this->createRules(), $this->messages());
		if ($this->validator->fails()) {
			return false;
		}
		return Question::create($data);
	}

	public function update(Question $question, $data)
	{
		$this->validator = Validator::make($data, $this->updateRules(), $this->messages());
		if ($this->validator->fails()) {
			return false;
		}
		return $question->update($data);
	}

	public function createRules()
	{
		return [
			'project_validation_id' => 'required|exists:project_validations,id', 
			'title' 				=> 'required'
		];
	}

	public function updateRules()
	{
		return [
			'title' => 'required'
		];
	}

	public function messages()
	{
		return [
			'project_validation_id.required' => 'Falha ao criar Questionário', 
			'project_validation_id.exists'   => 'Falha ao criar Questionário', 
			'title.required' 				 => 'Informe o título da Questão'
		];
	}

	public function getValidator()
	{
		StringUtil::toUrl($this->title."-".$this->id);
		return $this->validator;
	}
}