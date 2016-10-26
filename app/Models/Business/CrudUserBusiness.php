<?php 

namespace App\Models\Business;

use Validator;
use DB;
use Hash;
use Log;
use App\Utils\DateUtil;
use App\Utils\Utils;
use App\Jobs\SendUserToElastic;
use App\Models\Enums\EnumQueues;
use App\Models\DB\User;
use App\Models\DB\Work;
use App\Models\DB\Graduation;
use App\Models\Business\WorkBusiness;
use App\Models\Business\GraduationBusiness;
use App\Models\Business\ElasticExportBusiness;
use Illuminate\Foundation\Bus\DispatchesJobs;

class CrudUserBusiness 
{

	use DispatchesJobs;

	private $validator;

	public function create($userData) 
	{
		$works = null;
		$graduations = null;

		if (isset($userData['works'])) {
			$works = $userData['works'];
			unset($userData['works']);
		}

		if (isset($userData['graduations'])) {
			$graduations = $userData['graduations'];
			unset($userData['graduations']);
		}

		$this->validator = Validator::make($userData, $this->rules(), $this->messages());
		if ($this->validator->fails()) {
			return false;
		}

		try {
			DB::beginTransaction();
			$this->prepareData($userData);

			$user = User::create($userData);
			if ($works) {
				$workBusiness = new WorkBusiness();
				$idxWorks = 1;
				foreach ($works as $workData) {
					$workData['user_id'] = $user->id;
					$workData['order'] = $idxWorks;
					if ($workDB = $workBusiness->create($workData)) {
						$idxWorks++;
					} else {
						$this->validator->errors()->add('id', '<b>Informações Profissionais:</b>');
						$workValidator = $workBusiness->getValidator();
						$this->validator->errors()->merge($workValidator->errors());
						throw new \Exception('Work valdiation error');
					}
				}	
			}

			if ($graduations) {
				$graduationBusiness = new GraduationBusiness();
				$idxGraduation = 1;
				foreach ($graduations as $graduationData) {
					$graduationData['user_id'] = $user->id;
					$graduationData['order'] = $idxGraduation;
					if ($graduationDB = $graduationBusiness->create($graduationData)) {
						$idxGraduation++;
					} else {
						$this->validator->errors()->add('id', '<b>Informações Acadêmicas:</b>');
						$graduationValidator = $graduationBusiness->getValidator();
						$this->validator->errors()->merge($graduationValidator->errors());
						throw new \Exception('Graduation valdiation error');
					}
				}
			}

			$this->exportUser($user);

			DB::commit();

			return $user;
		} catch (\Exception $e) {
			DB::rollback();
			Log::error(Utils::getExceptionFullMessage($e));
			return false;
		}

	}

	public function update(User $user, $userData)
	{
		$works = null;
		$graduations = null;

		if (isset($userData['works'])) {
			$works = $userData['works'];
			unset($userData['works']);
		}

		if (isset($userData['graduations'])) {
			$graduations = $userData['graduations'];
			unset($userData['graduations']);
		}

		$this->validator = Validator::make($userData, $this->updateRules($user), $this->messages());
		if ($this->validator->fails()) {
			return false;
		}

		try {
			DB::beginTransaction();

			$this->prepareData($userData);

			$user->update($userData);

			if ($works) {
				$workBusiness = new WorkBusiness();
				$idxWorks = 0;
				foreach ($works as $workData) {
					$idxWorks++;
					$workDB = null;
					$workData['order'] = $idxWorks;
					$workData['user_id'] = $user->id;
					if (isset($workData['id'])) {
						$workDB = Work::where('id', '=',$workData['id'])
									  ->where('user_id', '=', $user->id)
									  ->firstOrFail();
						unset($workData['id']);
						if (!$workBusiness->update($workDB, $workData)) {
							$this->validator->errors()->add('id', '<b>Informações Profissionais:</b>');
							$workValidator = $workBusiness->getValidator();
							$this->validator->errors()->merge($workValidator->errors());
							throw new \Exception('Work validation error');
						}
					} else if (!$workDB = $workBusiness->create($workData))  {
						$this->validator->errors()->add('id', '<b>Informações Profissionais:</b>');
						$workValidator = $workBusiness->getValidator();
						$this->validator->errors()->merge($workValidator->errors());
						throw new \Exception('Work validation error');
					}
				}
			}

			if ($graduations) {
				$graduationBusiness = new GraduationBusiness();
				$idxGraduation = 0;
				foreach ($graduations as $graduationData) {
					$idxGraduation++;
					$graduationData['order'] = $idxGraduation;
					$graduationData['user_id'] = $user->id;
					if (isset($graduationData['id'])) {
						$graduationDB = Graduation::where('id','=',$graduationData['id'])
												  ->where('user_id','=',$user->id)
												  ->firstOrFail();
						unset($graduationData['id']);
						if (!$graduationBusiness->update($graduationDB, $graduationData)) {
							$this->validator->errors()->add('id', '<b>Informações Acadêmicas:</b>');
							$graduationValidator = $graduationBusiness->getValidator();
							$this->validator->errors()->merge($graduationValidator->errors());		
							throw new \Exception('Graduation valdiation error');
						}
					} else if (!$graduationDB = $graduationBusiness->create($graduationData)) {
						$this->validator->errors()->add('id', '<b>Informações Acadêmicas:</b>');
						$graduationValidator = $graduationBusiness->getValidator();
						$this->validator->errors()->merge($graduationValidator->errors());
						throw new \Exception('Graduation valdiation error');
					}
				}
			}

			$this->exportUser($user);

			DB::commit();

			return true;
		} catch (\Exception $e) {
			DB::rollback();
			Log::error(Utils::getExceptionFullMessage($e));
			return false;
		}
	}

	private function prepareData(&$data)
	{	
		if (isset($data['birth_date']) && is_string($data['birth_date']) && $data['birth_date']){
			$data['birth_date'] = DateUtil::strBrDateToDateTime($data['birth_date']);
		}

		if (isset($data['password']) && $data['password']) {
			$data['password'] = Hash::make($data['password']);
		}

		if (isset($data['skills']) && is_string($data['skills']) && $data['skills']) {
			$arrSkills = explode(',',$data['skills']);
			$sanatinzeds = [];
			foreach ($arrSkills as $skill) {
				array_push($sanatinzeds, trim($skill));
			}
			if (count($sanatinzeds)) {
				$data['skills'] = $sanatinzeds;
			} else {
				unset($data['skills']);
			}
		} 
	}	

	public function rules()
	{
		return [
			"name" 			=> 'required',
			"email" 		=> 'required|email|unique:users',
			"gender" 		=> 'required',
			"birth_date" 	=> 'required|date_format:d/m/Y',
			"password"		=> 'required_without:social_id|confirmed'
		];
	}

	public function updateRules($user)
	{
		return [
			"name" 			=> 'required',
			"email" 		=> 'required|email|unique:users,email,'.$user->id,
			"gender" 		=> 'required',
			"birth_date" 	=> 'required|date_format:d/m/Y',
		];
	}

	public function messages()
	{
		return [
			"name.required"   			 => 'Informe o Nome.',
			'email.required'  			 => 'Informe o E-mail.',
			'email.email'  			 	 => 'Informe um E-mail válido.',
			'email.unique'				 => 'O E-mail informado já está sendo utilizado.',
			'gender.required' 			 => 'Selecione o Sexo.',
			'birth_date.required' 		 => 'Informe a Data de Nascimento.',
			'birth_date.date_format' 	 => 'Informe uma Data de Nascimento válida.',
			'social_id.required_without' => 'Ocorreu um erro inesperado ao tentar realizar o cadastro.',
			'password.required_without'  => 'Informe a Senha.',
			'password.confirmed'		 => 'A confirmação de senha não bate com o valor informado.'
		];
	}

	public function getValidator()
	{
		return $this->validator;
	}

	private function dispathJob(User $user){
		$job = new SendUserToElastic($user);
		$this->dispatch($job->onQueue(EnumQueues::ELASTICSEARCH));
	}

	public static function dispathElasticJob(User $user){
		(new self())->dispathJob($user);
	}

	public function exportUser(User $user){
		$elasticExport = new ElasticExportBusiness();
		$elasticExport->exportUser($user);
	}

}