<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\DB\Project;
use App\Models\DB\User;

class Post extends Model{

	use SoftDeletes;

	protected $fillable = ['project_id', 'title', 'text', 'created_by', 'updated_by'];
	protected $dates =  ['created_at','updated_at','deleted_at'];

	public function project(){
		return $this->belongsTo(Project::class);
	}

	public function createUser(){
		return $this->belongsTo(User::class, 'created_by');
	}

	public function updateUser(){
		return $this->belongsTo(User::class, 'updated_by', 'id');
	}
	
}	