<?php

namespace App\Models\Business;

use App\Models\DB\User;
use App\Models\DB\Post;
use Validator;

class PostBusiness{

	private $validator;

	public function createPost($data, User $user){
		$data['created_by'] = $user->id;
		$this->validator = Validator::make($data, $this->validation(), $this->messages());
		if($this->validator->fails()){
			return false;
		}
		return Post::create($data);
	}

	public function updatePost($data, Post $post, User $user){
		$data['updated_by'] = $user->id;
		$this->validator = Validator::make($data, $this->validation(true), $this->messages());
		if($this->validator->fails()){
			return false;
		}
		return $post->update($data);
	}

	public function deletePost(Post $post, User $user){
		$post->update(['updated_by' => $user->id]);
		return $post->delete();
	}

	public function messages(){
		return [
			'title.required' 	=> 'Informe o nome',
			'title.max' 	 	=> 'O título deve ter no máximo 100 caracteres',
			'text.required'  	=> 'Informe o texto',
			'updated_by.exists' => 'Usuário não encontrado',
			'created_by.exists' => 'Usuário não encontrado'
		];
	}

	public function validation($updating = false){
		$data = [
			'title' 	 => 'required|max:100',
			'text'  	 => 'required',
			'project_id' => 'exists:projects,id'
		];
		if($updating){
			$data['updated_by'] = 'exists:users,id';
		} else {
			$data['created_by'] = 'exists:users,id';
		}
		return $data;
	}

	public function getValidator(){
		return $this->validator;
	}
}