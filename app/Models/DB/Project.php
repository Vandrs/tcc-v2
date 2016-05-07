<?php 

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;
use App\Models\DB\ProjectNote;
use App\Models\DB\Category;

class Project extends Model{

	CONST ACTIVE = 1;
	CONST INACTIVE = 0;

	protected $fillable = ['title', 'category_id','description', 'in_elastic'];

	protected static function boot(){
		parent::boot();
		Project::saving(function(Project $project){
			$project->in_elastic = 0;
		});
	}

	public function notes(){
		return $this->hasMany(ProjectNote::class);
	}

	public function category(){
		return $this->belongsTo(Category::class);
	}

	public function getAvgNote(){
		return ProjectNote::where('project_id', '=', $this->id)->avg('note');
	}

	public function getTotalNotes(){
		return ProjectNote::where('project_id', '=', $this->id)->count();	
	}
	
}