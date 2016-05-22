<?php

namespace App\Http\Controllers;

use App\Models\Business\ImageBusiness;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\Glide\ServerFactory;
use App\Http\Requests;
use App\Models\Business\TempBusiness;
use App\Models\DB\Project;
use App\Models\DB\Image;
use App\Models\Enums\EnumCapabilities;
use App\Utils\Utils;
use Storage;
use Gate;
use Log;

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

	public function create(Request $request, $projectId){
		try{
			$project = Project::findOrFail($projectId);
			if(Gate::denies(EnumCapabilities::UPDATE_PROJECT,$project)){
				return $this->ajaxNotAllowed();
			}
			$imageBusiness = new ImageBusiness();
			if($image = $imageBusiness->create($request->file('image'), $project)){
				return json_encode([
					"status" => 1,
					"image" => ['id' => $image->id, 'url' => $image->getImageUrl()]
				]);
			} else {
				$msg = implode("<br />", $imageBusiness->getValidator()->erros()->all());
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

	public function update(Request $request, $projectId){
		try{
			$project = Project::findOrFail($projectId);
			if(Gate::denies(EnumCapabilities::UPDATE_PROJECT,$project)){
				return $this->ajaxNotAllowed();
			}
			$image = Image::findOrFail($request->input('id'));
			$imageBusiness = new ImageBusiness();
			$imageBusiness->update($image, $request->only(['title','cover']), $project);
			return json_encode([
				"status" => 1,
				"msg" => trans('custom_messages.update_success'),
				"class_msg" => "alert-success"
			]);
		} catch(\Exception $e){
			return $this->ajaxUnexpectedError();
		}
	}

	public function delete(Request $request, $projectId){
		try{
			$project = Project::findOrFail($projectId);
			if(Gate::denies(EnumCapabilities::UPDATE_PROJECT,$project)){
				return $this->ajaxNotAllowed();
			}
			$image = Image::findOrFail($request->input('id'));
			$imageBusiness = new ImageBusiness();
			$imageBusiness->delete($image, $project);
			return json_encode([
				"status" => 1,
				"msg" => trans('custom_messages.delete_success'),
				"class_msg" => "alert-success"
			]);
		} catch(\Exception $e){
			return $this->ajaxUnexpectedError($e->getMessage());
		}
	}
}
