<?php

namespace App\Models\Business;

use Validator;

class MailBusiness {

	private $data;
	private $validator;


	public function __construct($data){
		$this->data = $data;
	}

	public function isValid(){
		$this->validator = Validator::make($this->data,$this->validation(),$this->messages());
		if($this->validator->passes()){
			if(view()->exists($this->data['view'])){
				return true;
			} else {
				$this->validator->errors()->add('view','View não encontrada');
			}
		}
		return false;
	}

	public function validation(){
		return [
			"to"   	  => 'required|array',
			"view" 	  => 'required',
			"subject" => 'required'
		];
	}

	public function messages(){
		return [
			"to.required"   => "Campo to deve ter ao menos 1 destinatário",
			"to.array" 	    => "Campo to com formato inválido",
			"view.required" => "Campo view é obrigatório"
		];
	}

	public function getStructure(){
		return [
			'sender'  	  => ['address' => 'email remetente', 	 'name' => 'nome remetente'],
			'to'      	  => ['address' => 'email destinatário', 'name' => 'nome destinatário'],
			'replyTo'	  => ['address' => 'email resposta', 	 'name' => 'nome usuario resposta'],
			'subject' 	  => 'assunto do email',
			'attachments' => ['caminho anexo 1', 'caminho anexo 2'],
			'view' 	  	  => 'caminho da view',
			'view_data'   => ['dados extras a serem enviados para view']
		];
	}

	public function getValidator(){
		return $this->validator;
	}

	public function getData(){
		return $this->data;
	}

}