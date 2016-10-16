<?php

namespace App\Models\Business;

use Validator;
use App\Utils\DateUtil;
use App\Models\DB\Graduation;

class GraduationBusiness
{

	private $validator;

	public function create($data)
	{
		$this->validator = Validator::make($data, $this->rules(), $this->messages());
		if ($this->validator->fails()) {
			return false;
		}
		$this->prepareData($data);
		return Graduation::create($data);
	}

	public function prepareData(&$data)
	{
		if (isset($data['conclusion_at']) && is_string($data['conclusion_at']) && $data['conclusion_at']) {
			$data['conclusion_at'] = DateUtil::strBrDateToDateTime($data['conclusion_at'], false);
		}
	}	

	public function rules()
	{
		return [
			'user_id' 		=> 'required',
			'course' 		=> 'required',
			'institution' 	=> 'required',
			'conclusion_at' => 'required|date_format:d/m/Y'
		];
	}

	public function messages()
	{
		return [
			'user_id.required' 			=> 'Erro inesperado.',
			'course.required' 			=> 'Informe o Curso.',
			'institution.required' 		=> 'Informe a Instituição de Ensino.',
			'conclusion_at.required'    => 'Informe a Data de Conclusão.',
			'conclusion_at.date_format' => 'Data de Conclusão deve possuir um formato válido.'
		];
	}

	public function getValidator()
	{
		return $this->validator;
	}
}