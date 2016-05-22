<?php

namespace App\Models\Business;
use App\Models\DB\File;
use App\Models\DB\Project;
use App\Models\Business\TempBusiness;
use App\Models\Business\CrudProjectBusiness;
use App\Utils\Utils;
use Illuminate\Http\UploadedFile;
use Storage;
use Validator;

class FileBusiness {

    private $validator;

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

    public function create(UploadedFile $file, Project $project){
        $tempBusiness = new TempBusiness;
        $this->validator = Validator::make(['file' => $file], $tempBusiness->fileValidation(), $tempBusiness->messages());
        if($this->validator->fails()){
            return false;
        }
        $name = str_replace(".".$file->guessClientExtension(),'',$file->getClientOriginalName());
        $fileName = Utils::radomName().".".$file->guessClientExtension();
        $file->move(storage_path('files'),$fileName);
        $createData = [
            "file" => $fileName,
            "title" => $name.".".$file->guessClientExtension(),
            "project_id" => $project->id
        ];
        if($file = File::create($createData)){
            CrudProjectBusiness::dispathElasticJob($project);
            return $file;
        }
        $this->validator->errors()->add('unexpected',trans('custom_messages.unexpected_error'));
        return false;
    }

    public function delete(File $file, Project $project){
        $disk = $this->getDisk();
        if($disk->exists($file->file)){
            $disk->delete($file->file);
        }
        $file->delete();
        CrudProjectBusiness::dispathElasticJob($project);
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

    public function getValidator(){
        return $this->getValidator();
    }
}