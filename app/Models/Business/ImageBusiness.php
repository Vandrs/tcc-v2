<?php 

namespace App\Models\Business;
use App\Models\DB\Project;
use App\Models\DB\Image;
use App\Models\Business\CrudProjectBusiness;
use App\Models\Business\TempBusiness;
use App\Utils\Utils;
use Illuminate\Http\UploadedFile;
use DB;
use Storage;
use Validator;

class ImageBusiness{

	private $validator;

	public function createFromTempFile($data, Project $project){
		$tempDisk =  $this->getTempDisk();
		if($tempDisk->exists($data['file'])){
			$content = $tempDisk->get($data['file']);
			$disk = $this->getDisk();
			$disk->put($data['file'],$content);
			return Image::create([
				'project_id' => $project->id,
				'title' 	 => $data['name'],
				'file' 		 => $data['file'],
				'cover' 	 => $data['cover']
			]);
		}
	}

	public function simpleUpload(UploadedFile $file){
		$tempBusiness = new TempBusiness;
		$this->validator = Validator::make(
			['image' => $file ], 
			$tempBusiness->imageValidation(), 
			$tempBusiness->messages()
		);
		if($this->validator->fails()){
			return false;
		}
		$fileName = Utils::radomName().".".$file->guessClientExtension();
		$file->move(storage_path('images'),$fileName);
		return 	route('image.get',['path' => $fileName]);	

	}

	public function create(UploadedFile $file, Project $project){
		$tempBusiness = new TempBusiness;
		$this->validator = Validator::make(['image' => $file ], $tempBusiness->imageValidation(), $tempBusiness->messages());
		if($this->validator->fails()){
			return false;
		}
		$fileName = Utils::radomName().".".$file->guessClientExtension();
		$file->move(storage_path('images'),$fileName);
		if($image = Image::create(['file' => $fileName, 'project_id' => $project->id])){
			CrudProjectBusiness::dispathElasticJob($project);
			return $image;
		}
		$this->validator->errors()->add('unexpected', trans('custom_messages.unexpected_error'));
		return false;
	}

	public function update(Image $image, $data, Project $project){
		$cover = isset($data['cover'])? $data['cover'] : null;
		if((int) $cover === 1 && (int) $image->cover !== 1){
			$this->removeCoverImages($image->id, $project->id);
		}
		$image->update([
			'title'    => isset($data['title'])? $data['title'] : null,
			'cover'    => $cover
		]);
		CrudProjectBusiness::dispathElasticJob($project);
	}

	public function delete(Image $image, Project $project){
		$disk = $this->getDisk();
		if($disk->exists($image->file)){
			$disk->delete($image->file);
		}
		$image->delete();
		CrudProjectBusiness::dispathElasticJob($project);
	}

	public function removeCoverImages($excludeId, $projectId){
		DB::table('images')
		  ->where('id','<>',$excludeId)
		  ->where('project_id','=',$projectId)
		  ->update(['cover' => 0]);
	}

	public function getTempDisk(){
		return Storage::disk('temp');
	}

	public function getDisk(){
		return Storage::disk('images');
	}

	public function deleteTempFile($file){
		$tempDisk = $this->getTempDisk();
		if($tempDisk->exists($file)){
			$tempDisk->delete($file);
		}
	}

	public function getValidator(){
		return $this->getValidator();
	}
}
