<?php

namespace App\Models\Business;

use Validator;
use DB;
use Log;
use App\Utils\Utils;
use App\Models\DB\ProjectValidation;
use App\Models\DB\Question;
use App\Models\DB\Answer;
use App\Models\DB\Validation;

class ValidationBusiness 
{
	private $validator;

	public function create(ProjectValidation $projectValidation, $data)
	{
		$this->validator = Validator::make($data, $this->rules(), $this->messages());

		if (!$this->canValidate($projectValidation)) {
			$this->validator->errors()->add('project_validation_id','A validação em questão foi removida pelos autores do projeto.');
		}

		if ($this->validator->fails()) {
			return false;
		}

		if ($projectValidation->questions()->count() != count($data['question'])) {
			$this->validator->errors()->add('empty_question','Preencha todas as questões.');			
			return false;
		};

		try {
			DB::beginTransaction();
			$questionsAnswers = $data['question'];
			unset($data['question']);
			$data['project_validation_id'] = $projectValidation->id;
			$validation = Validation::create($data);
			if ($this->makeQuestions($validation, $projectValidation, $questionsAnswers)) {
				DB::commit();
				return $validation;
			} else {
				$this->validator->errors()->add('unexpectd',trans('custom_messages.unexpected_error'));
				throw new \Exception("Falha ao salvar respostas");
			}
			return false;
		} catch (\Exception $e) {	
			Log::error(Utils::getExceptionFullMessage($e));
			DB::rollback();
			return false;
		}
	}

	private function makeQuestions(Validation $validation, ProjectValidation $projectValidation,  $questions)
	{
		$valid = true;
		foreach ($questions as $id => $response) {
			$exists = Question::where('id', '=', $id)
							  ->where('project_validation_id', '=', $projectValidation->id)
				    		  ->exists();
			if ($exists) {
				$createData = [
					"validation_id" => $validation->id,
					"question_id" 	=> $id,
					"option" 		=> $response
				];
				$answer = Answer::create($createData);
			} else {
				$valid = false;
				break;
			}
		}
		return $valid;
	}

	public function rules()
	{
		return [
			'name' 		 => 'required',
			'email'		 => 'email',
			'occupation' => 'required',
			'gender' 	 => 'required',
			'age'		 => 'required|numeric|min:1',
			'question'	 => 'required',
			'recommend'  => 'required'
		];
	}

	public function messages()
	{
		return [
			'name.required' 	  => 'Informe o Nome.',
			'email.email'   	  => 'Informe um E-mail válido',
			'occupation.required' => 'Informe a Ocupação',
			'gender.required'     => 'Informe o Sexo',
			'age.required'		  => 'Informe a Idade',
			'age.numero'		  => 'A Idade deve ser um número positivo',
			'age.min'			  => 'A Idade não pode ser um múmero nesgativo',
			'question.required'	  => 'Preencha todas as questões.',
			'recommend.required'  => 'Indique se você recomendário ou não o projeto'
		];
	}

	public function canValidate(ProjectValidation $projectValidation)
	{
		if ($projectValidation->trashed() || !$projectValidation->isInValidationPeriod()) {
			return false;
		}
		return true;
	}

	public function getValidator()
	{
		return $this->validator;
	}
}