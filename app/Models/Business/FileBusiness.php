<?php

namespace App\Models\Business;
use App\Models\DB\File;
use App\Models\DB\Project;
use Storage;

class FileBusiness {
    public function createFromTempFile($data, Project $project){
        $tempDisk =  $this->getTempDisk();
        if($tempDisk->exists($data['file'])){
            $content = $tempDisk->get($data['file']);
            $disk = $this->getDisk();
            $disk->put($data['file'],$content);
            return File::create([
                'project_id' => $project->id,
                'title' 	 => $data['title'],
                'file' 		 => $data['file']
            ]);
        }
    }

    public function getFile($fileName){
        $disk = $this->getDisk();
        if($disk->exists($fileName)){
            return $disk->get($fileName);
        }
        return false;
    }

    public function getTempDisk(){
        return Storage::disk('temp');
    }

    public function getDisk(){
        return Storage::disk('files');
    }

    public function deleteTempFile($file){
        $tempDisk = $this->getTempDisk();
        if($tempDisk->exists($file)){
            $tempDisk->delete($file);
        }
    }
}