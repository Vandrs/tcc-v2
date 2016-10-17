<?php 

namespace App\Models\Business;

use Validator;
use App\Utils\DateUtil;
use App\Models\DB\Work;

class WorkBusiness{
	private $validator;

	public function create($data)
	{
		$this->validator = Validator::make($data, $this->rules(), $this->messages());
		if ($this->validator->fails()){
			return false;
		}
		$this->parseDates($data);
		return Work::create($data);
	}

	public function update(Work $work, $data)
	{
		$this->validator = Validator::make($data, $this->rules(), $this->messages());
		if ($this->validator->fails()){
			return false;
		}
		$this->parseDates($data);
		return $work->update($data);
	}

	private function parseDates(&$data)
	{
		if (isset($data['started_at']) && is_string($data['started_at']) && $data['started_at']) {
			$data['started_at'] = DateUtil::strBrDateToDateTime($data['started_at'], false);
		}

		if (isset($data['ended_at']) && is_string($data['ended_at']) && $data['ended_at']) {
			$data['ended_at'] = DateUtil::strBrDateToDateTime($data['ended_at'], false);
		} else if(isset($data['ended_at']) && empty($data['ended_at'])) {
			unset($data['ended_at']);
		}
	}

	public function getValidator()
	{
		return $this->validator;
	}

	public function rules()
	{
		return [
			'user_id'    => 'required|exists:users,id',
			'title'		 => 'required',
			'company'    => 'required',
			'started_at' => 'required|date_format:d/m/Y',
			'ended_at'   => 'date_format:d/m/Y'
		];
	}

	public function messages()
	{
		return [
			'user_id.required'    	 => 'Erro inesperado',
			'user_id.exists'	  	 => 'Erro inesperado',
			'title.required'      	 => 'Informe o Cargo',
			'company.required'    	 => 'Informe a Empresa',
			'started_at.required' 	 => 'Informe a Data de Início',
			'started_at.date_format' => 'Data de Início de conter um formato válido',
			'ended_at.date_format'   => 'Data Fim deve conter um formato válido'
		];
	}
}