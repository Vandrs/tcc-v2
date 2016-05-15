<?php

namespace App\Models\Business;

use Storage;
use App\Utils\Utils;
use Illuminate\Http\UploadedFile;
use Image;
use Validator;

class TempBusiness{

	private $validator;

	public function uploadImage(UploadedFile $file){
		$this->validator = Validator::make(['image' => $file], $this->imageValidation(), $this->messages());
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

	public function uploadFile(UploadedFile $file){
		$this->validator = Validator::make(['file' => $file], $this->fileValidation(), $this->messages());
		if($this->validator->fails()){
			return false;
		}
		$name = str_replace(".".$file->guessClientExtension(),'',$file->getClientOriginalName());
		$fileName = Utils::radomName().".".$file->guessClientExtension();
		$file->move(storage_path('temp'),$fileName);
		return [
			'file'  => $fileName,
			'title'  => $name.".".$file->guessClientExtension()
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

	public function imageValidation(){
		return ['image' => 'required|image|max:5120'];
	}

	public function fileValidation(){
		return ['file' => 'required|mimes:pdf,ppt,pptx,doc,docx|max:10240'];
	}

	public function messages(){
		return [
			'image.required' => 'Informe a imagem',
			'image.image' 	=> 'Imagem deve com formato inválido. Extensões permitidas(jpeg\jpg, png, gif)',
			'image.max' 	=> 'Tamanho máximo permitido por imagem 5MB',
			'file.required' => 'Informe o arquivo',
			'file.max' => 'O arquivo deve ter no máximo 10MB',
			'file.mimes' => 'Arquivo deve com formato inválido. Extensões permitidas(pdf, ppt, pptx, doc, docx)',
		];
	}

	public function getDisk(){
		return Storage::disk('temp');
	}

	public function getValidator(){
		return $this->validator;
	}

}