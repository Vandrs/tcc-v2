<?php

namespace App\Models\Business;
use App\Models\DB\User;
use App\Models\DB\Project;
use App\Models\DB\UserProject;
use App\Models\Enums\EnumProject;
use App\Models\Business\UserProjectBusiness;
use App\Models\Business\ImageBusiness;
use App\Utils\Utils;
use Validator;
use DB;
use Log;

class CrudProjectBusiness{

	private $validator;

	public function create($data, User $user){
		$images = $data['image_files'];
		unset($data['image_files']);
		$this->validator = Validator::make($data,$this->validation(),$this->messages());
		if($this->validator->fails()){
			return false;
		}
		DB::beginTransaction();
		try{
			$project = Project::create($data);
			$userProjectBusiness = new UserProjectBusiness;
			if(!$userProjectBusiness->create($user, $project, EnumProject::ROLE_OWNER)){
				throw new \Exception('Erro ao relacionar projeto e usuário');
			}
			if($images){
				$images = json_decode($images,true);
				if(is_array($images)){
					$imageBusiness = new ImageBusiness;
					foreach($images as $image){
						$imageObj = $imageBusiness->createFromTempFile($image,$project);
					}
					foreach($images as $image){
						$imageBusiness->deleteTempFile($image['file']);
					}
				}
			}
			DB::commit();
			return $project;
		}catch(\Exception $e){
			Log::error(Utils::getExceptionFullMessage($e));
			$this->validator->errors()->add('generic',trans('custom_messages.unexpected_error'));
			DB::rollback();
			return false;
		}
	}

	public function validation(){
		return [
			'title' 	  => 'required|max:100',
			'description' => 'required',
			'category_id' => 'required|exists:categories,id'
		];
	}

	public function messages(){
		return [
			'title.required' 	   => 'Informe o Título',
			'title.max' 		   => 'O título deve ter no máximo 100 caracteres',
			'description.required' => 'Informe a descrição',
			'category_id.required' => 'Informe a categoria',
			'category_id.exists'   => 'A Categoria informada não foi encontrada'
		];
	}

	public function getValidator(){
		return $this->validator;
	}
}
