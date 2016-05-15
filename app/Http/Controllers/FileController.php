<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;

use App\Models\Business\TempBusiness;
use App\Models\DB\File;

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
}
