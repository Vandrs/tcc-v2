<?php

namespace App\Models\Business;

use Storage;
use App\Utils\Utils;
use Illuminate\Http\UploadedFile;
use Image;
use Validator;

class TempImageBusiness{

	private $validator;

	public function upload(UploadedFile $file){
		$this->validator = Validator::make(['image' => $file], $this->validation(), $this->messages());
		if($this->validator->fails()){
			return false;
		}
		$fileName = Utils::radomName().".".$file->guessClientExtension();
		$file->move(storage_path('temp'),$fileName);
		return [
			'file'  => $fileName, 
			'name'  => null,
			'cover' => 0,
			'url'   => route('image.temp-file', ['file' => $fileName] )
		];
	}

	public function deleteFile($file){
		$disk = $this->getDisk();
		if($disk->exists($file)){
			$disk->delete($file);
		}
	}

	public function getImage($file){
		$disk = $this->getDisk();
		if($disk->exists($file)){
			return Image::make($disk->get($file));
		}
		return null;
	}

	public function validation(){
		return ['image' => 'required|image|max:5120'];
	}

	public function messages(){
		return [
			'image.requide' => 'Informe a imagem',
			'image.image' 	=> 'Imagem deve com formato inválido. Extensões permitidas(jpeg\jpg, png, gif)',
			'image.size' 	=> 'Tamanho máximo permitido por imagem 5MB'
		];
	}

	public function getDisk(){
		return Storage::disk('temp');
	}

	public function getValidator(){
		return $this->validator;
	}

}