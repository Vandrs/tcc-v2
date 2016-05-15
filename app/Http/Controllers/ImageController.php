<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\Glide\ServerFactory;
use App\Http\Requests;
use App\Models\Business\TempBusiness;
use Storage;

class ImageController extends Controller {

	public function getImage(Request $request, $path){
		$imageParameters = $request->input();
		$glideServer = ServerFactory::create([
			'source' => Storage::disk('images')->getDriver(),
			'cache'  => Storage::disk('image_cache')->getDriver(),
		]);
		try{
			return response($glideServer->outputImage($path,$imageParameters));
		} catch (\Exception $e){
			return response('Arquivo não encontrado', Response::HTTP_NOT_FOUND);
		}
	}

    public function tempUpload(Request $request){
    	$imageFile = $request->file('image');
    	$tempImage = new TempBusiness;
    	if($data = $tempImage->uploadImage($imageFile)){
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
    	$tempImage = new TempBusiness;
    	if($image = $tempImage->getImage($file)){
    		return $image->response();
    	}
    	return response('Arquivo não encontrado',Response::HTTP_NOT_FOUND);
    }

    public function deleteTempFile(Request $request){
        $fileName = $request->input('file_name');
        $tempImage = new TempBusiness;
        try{
            $tempImage->deleteFile($fileName);
            return json_encode(['status' => 1]);
        } catch(\Exception $e){
            return json_encode(['status' => 0, 'msg' => 'Falha ao excluir imagem', 'class_msg' => 'alert-danger']);
        }
    }
}
