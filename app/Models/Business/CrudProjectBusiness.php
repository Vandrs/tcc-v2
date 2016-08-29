<?php

namespace App\Models\Business;
use App\Models\DB\User;
use App\Models\DB\Project;
use App\Models\DB\UserProject;
use App\Models\Interfaces\C3Project;
use App\Models\Elastic\Models\ElasticProject;
use App\Models\Enums\EnumProject;
use App\Models\Enums\EnumQueues;
use App\Models\Business\UserProjectBusiness;
use App\Models\Business\ImageBusiness;
use App\Models\Business\FileBusiness;
use App\Utils\Utils;
use Validator;
use DB;
use Log;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\SendPropertyToElastic;
use App\Jobs\NotifyProjectFollowers;

class CrudProjectBusiness{

	use DispatchesJobs;

	private $validator;

	public function create($data, User $user){
		$images = $data['image_files'];
		$files  = $data['files'];
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
					$this->uploadImages($images, $project);
				}
			}
			if($files){
				$files = json_decode($files,true);
				if(is_array($files)){
					$this->uploadFiles($files, $project);
				}
			}
			DB::commit();
			$this->exportProject($project);
			return $project;
		}catch(\Exception $e){
			Log::error(Utils::getExceptionFullMessage($e));
			$this->validator->errors()->add('generic',trans('custom_messages.unexpected_error'));
			DB::rollback();
			return false;
		}
	}

	public function update(Project $project, $data){
		$this->validator = Validator::make($data,$this->validation(),$this->messages());
		if($this->validator->fails()){
			return false;
		}
		if($project->update($data)){
			$this->exportProject($project);
			return $project;
		}
	}

	public function delete(Project $project){
		try{
			ElasticProject::deleteById($project->id);
			$project->delete();
			return true;
		} catch(\Exception $e){
			Log::error(Utils::getExceptionFullMessage($e));
			return false;
		}
	}

	private function uploadImages($images, Project $project){
		$imageBusiness = new ImageBusiness;
		foreach($images as $image){
			$imageObj = $imageBusiness->createFromTempFile($image,$project);
			$imageBusiness->deleteTempFile($image['file']);
		}
	}

	private function uploadFiles($files, Project $project){
		$fileBusiness = new FileBusiness;
		foreach($files as $file){
			$fileObj = $fileBusiness->createFromTempFile($file, $project);
			$fileBusiness->deleteTempFile($file['file']);
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

	private function dispathJob(Project $project){
		$job = new SendPropertyToElastic($project);
		$this->dispatch($job->onQueue(EnumQueues::ELASTICSEARCH));
	}

	public static function dispatchNotificationJob(Project $project){
		$job = (new NotifyProjectFollowers($project))->onQueue(EnumQueues::NOTIFICATION);
		(new self())->dispatch($job);
	}

	public static function dispathElasticJob(Project $project){
		(new self())->dispathJob($project);
	}

	public function exportProject(Project $project){
		$elasticExport = new ElasticExportBusiness();
		$elasticExport->exportProject($project);
	}

	public static function extractProjetoFromC3Project(C3Project $c3Project){
		if($c3Project instanceof Project){
			return $c3Project;
		}
		return Project::findOrFail($c3Project->id);
	}
}
