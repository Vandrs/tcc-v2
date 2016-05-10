<?php 

namespace App\Models\Business;
use App\Models\DB\Project;
use App\Models\DB\Image;
use Storage;

class ImageBusiness{

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
}
