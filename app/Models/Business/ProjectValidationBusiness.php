<?php 

namespace App\Models\Business;

use Validator;
use Log;
use DB;
use App\Utils\Utils;
use App\Utils\DateUtil;
use App\Models\DB\ProjectValidation;
use App\Models\DB\Question;
use App\Models\Business\QuestionBusiness;

class ProjectValidationBusiness
{


	private $validator;

	public function create($data)
	{
		try {
			DB::beginTransaction();
			$startedAt = isset($data['started_at']) ? $data['started_at'] : "";
			$this->validator = Validator::make($data, $this->rules($startedAt), $this->messages());
			if ($this->validator->fails()) {
				throw new \Exception('Erro ao criar formulário de pesquisa');
			}
			if (isset($data['question']) && !empty($data['question'])) {
				$questions = $data['question'];
				unset($data['question']);
				$this->prepareData($data);
				$projectValidation = ProjectValidation::create($data);
				$questionBusiness = new QuestionBusiness();
				foreach ($questions as $questionData) {
					$questionData['project_validation_id'] = $projectValidation->id;
					if (!$questionBusiness->create($questionData)) {
						$questionValidator = $questionBusiness->getValidator();
						$this->validator->errors()->merge($questionValidator->errors());
						throw new \Exception('Questão com informações inválida');
					}	
				}
				DB::commit();
				return $projectValidation;
			} else {
				$this->validator->errors()->add('question','Informe o Título das Questões');
				throw new \Exception('Questão com informações inválida');
			}
		} catch (\Exception $e) {
			DB::rollback();
			Log::error(Utils::getExceptionFullMessage($e));
			return false;
		}
	}

	public function update(ProjectValidation $projectValidation, $data)
	{
		try {
			DB::beginTransaction();
			$startedAt = isset($data['started_at']) ? $data['started_at'] : "";
			$this->validator = Validator::make($data, $this->updateRules($startedAt), $this->messages());
			if ($this->validator->fails()) {
				throw new \Exception('Erro ao alterar formulário de pesquisa');
			}
			if (isset($data['question']) && !empty($data['question'])) {
				$questions = $data['question'];
				unset($data['question']);
				$this->prepareData($data);
				$projectValidation->update($data);
				$questionBusiness = new QuestionBusiness();
				foreach ($questions as $questionData) {
					$questionData['project_validation_id'] = $projectValidation->id;
					if (isset($questionData['id'])) {
						$question = Question::findOrFail($questionData['id']);
						unset($questionData['id']);
						if (!$questionBusiness->update($question, $questionData)) {
							$questionValidator = $questionBusiness->getValidator();
							$this->validator->errors()->merge($questionValidator->errors());
							throw new \Exception('Questão com informações inválidas (alteração)');
						}
					} else {
						if (!$questionBusiness->create($questionData)) {
							$questionValidator = $questionBusiness->getValidator();
							$this->validator->errors()->merge($questionValidator->errors());
							throw new \Exception('Questão com informações inválidas');
						}	
					}
				}
				DB::commit();
				return $projectValidation;
			} else {
				$this->validator->errors()->add('question','Informe o Título das Questões');
				throw new \Exception('Questão com informações inválida');
			}
		} catch (\Exception $e) {
			DB::rollback();
			Log::error(Utils::getExceptionFullMessage($e));
			return false;
		}
	}

	public function rules($startedAt)
	{
		return [
			"project_id" => 'required|exists:projects,id',
			"title" 	 => 'required',
			"started_at" => 'required|date_format:d/m/Y', 
			"ended_at" 	 => 'required|date_format:d/m/Y|after:'.$startedAt
		];
	}

	public function updateRules($startedAt)
	{
		return [
			"title" 	 => 'required',
			"started_at" => 'required|date_format:d/m/Y', 
			"ended_at" 	 => 'required|date_format:d/m/Y|after:'.$startedAt
		];
	}

	public function messages()
	{
		return [
			"project_id.required"    => "Ocorreu um erro inesperado ao tentar criar o questionario",
			"project_id.exists"      => "Projeto não encontrado",
			"title.required"	     => "Informe o título do questionário",
			"started_at.required"    => "Informe a Data de Início",
			"started_at.date_format" => "Date de Início com formato inválido",
			"ended_at.required"		 => "Informe a Data de Término",
			"ended_at.date_format"   => "Data de Término com formato inválido",
			"ended_at.after"   		 => "Data de Término deve ser maior ou igual a Data de Início",
		];
	}

	public function getValidator()
	{
		return $this->validator;
	}

	private function prepareData(&$data)
	{
		$data['started_at'] = DateUtil::strBrDateToDateTime($data['started_at']);
		$data['ended_at'] = DateUtil::strBrDateToDateTime($data['ended_at']);
	}
}