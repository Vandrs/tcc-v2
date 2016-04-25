<?php 

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;
use App\Models\DB\ProjectNote;

class Project extends Model{

	CONST ACTIVE = 1;
	CONST INACTIVE = 0;

	protected $fillable = ['title', 'description', 'in_elastic'];

	protected static function boot(){
		parent::boot();
		Project::saving(function(Project $project){
			$project->in_elastic = 0;
		});
	}

	public function notes(){
		return $this->hasMany(ProjectNote::class);
	}

	public function getAvgNote(){
		return ProjectNote::where('project_id', '=', $this->id)->avg('note');
	}

	public function getTotalNotes(){
		return ProjectNote::where('project_id', '=', $this->id)->count();	
	}
	
}