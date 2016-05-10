<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Models\Business\TempImageBusiness;

class ImageController extends Controller
{
    public function tempUpload(Request $request){
    	$imageFile = $request->file('image');
    	$tempImage = new TempImageBusiness;
    	if($data = $tempImage->upload($imageFile)){
    		return json_encode([ 
    			'status' 	=> 1,
    			'file' 		=> $data
    		]);
    	} else {
    		return json_encode([ 
    			'status' 	=> 0, 
    			'class_msg' => 'alert-danger',
    			'msg' 		=> implode('<br />',$tempImage->getValidator()->messages()->all())
    		]);
    	}
    }

    public function tempFile(Request $request, $file){
    	$tempImage = new TempImageBusiness;
    	if($image = $tempImage->getImage($file)){
    		return $image->response();
    	}
    	return response('',Response::HTTP_NOT_FOUND);
    }

    public function deleteTempFile(Request $request){
        $fileName = $request->input('file_name');
        $tempImage = new TempImageBusiness;
        try{
            $tempImage->deleteFile($fileName);
            return json_encode(['status' => 1]);
        } catch(\Exception $e){
            return json_encode(['status' => 0, 'msg' => 'Falha ao excluir imagem', 'class_msg' => 'alert-danger']);
        }
    }
}
