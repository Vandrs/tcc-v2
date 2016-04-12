<?php 

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;
use App\Models\DB\User;
use App\Models\DB\Project;

class ProjectNote extends Model{
	protected $fillable = ['user_id', 'project_id', 'note'];


	public function user(){
		return $this->belongsTo(User::class);
	}

	public function project(){
		return $this->belongsTo(Project::class);
	}
}