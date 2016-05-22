<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;

use App\Models\Business\TempBusiness;
use App\Models\Business\FileBusiness;
use App\Models\DB\File;
use App\Models\DB\Project;
use App\Models\Enums\EnumCapabilities;
use App\Utils\Utils;
use Gate;
use Log;

class FileController extends Controller{

    public function get($path){
        try{
            $file = File::where('file','=',$path)->firstOrFail();
            return response()->download(storage_path('files/'.$file->file),$file->title);
            throw new \Exception('Arquivo não encontrado');
        } catch(\Exception $e){
            return response('Arquivo não encontrado', Response::HTTP_NOT_FOUND);
        }
    }

    public function tempUpload(Request $request){
        $file = $request->file('file');
        $tempFile = new TempBusiness;
        if($data = $tempFile->uploadFile($file)){
            return json_encode([
                'status' 	=> 1,
                'file' 		=> $data
            ]);
        } else {
            return json_encode([
                'status' 	=> 0,
                'class_msg' => 'alert-danger',
                'msg' 		=> implode('<br />',$tempFile->getValidator()->messages()->all())
            ]);
        }
    }

    public function create(Request $request, $projectId){
        try{
            $project = Project::findOrFail($projectId);
            if(Gate::denies(EnumCapabilities::UPDATE_PROJECT,$project)){
                return $this->ajaxNotAllowed();
            }
            $fileBusiness = new FileBusiness;
            if($file = $fileBusiness->create($request->file('file'), $project)){
                return json_encode([
                    "status" => 1,
                    "file" => ['id' => $file->id, 'url' => $file->url, 'title' => $file->title]
                ]);
            } else {
                $msg = implode("<br />", $file->getValidator()->erros()->all());
                return json_encode([
                    "status" => 0,
                    "msg" => $msg,
                    "class_msg" => "alert-danger"
                ]);
            }
        } catch(\Exception $e){
            Log::error(Utils::getExceptionFullMessage($e));
            return $this->ajaxUnexpectedError();
        }
    }

    public function delete(Request $request, $projectId){
        try{
            $project = Project::findOrFail($projectId);
            if(Gate::denies(EnumCapabilities::UPDATE_PROJECT,$project)){
                return $this->ajaxNotAllowed();
            }
            $file = File::findOrFail($request->input('id'));
            $fileBusiness = new FileBusiness();
            $fileBusiness->delete($file, $project);
            return json_encode([
                "status" => 1,
                "msg" => trans('custom_messages.delete_success'),
                "class_msg" => "alert-success"
            ]);
        } catch(\Exception $e){
            Log::error(Utils::getExceptionFullMessage($e));
            return $this->ajaxUnexpectedError();
        }
    }

}
