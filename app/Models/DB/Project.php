<?php 

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;
use App\Models\DB\ProjectNote;
use App\Models\DB\Category;
use App\Models\DB\User;
use App\Models\DB\Image;
use App\Models\DB\File;
use App\Models\Enums\EnumProject;
use DB;

class Project extends Model{

	CONST ACTIVE = 1;
	CONST INACTIVE = 0;

	protected $fillable = ['title', 'category_id','description', 'urls', 'in_elastic'];

	protected $casts = [
		'urls' => 'array'
	];

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

	public function images(){
		return $this->hasMany(Image::class);
	}

	public function files(){
		return $this->hasMany(File::class);
	}

	public function imageCoverOrFirst(){
		$image = $this->images->where("cover",1,false)->first();
		if($image){
			return $image;
		} else {
			return $this->images->first();
		}
	}

	public function getMemberUsers(){
		$roles = [ EnumProject::ROLE_CONTRIBUTOR, EnumProject::ROLE_OWNER, EnumProject::ROLE_MENTOR] ;
		return User::select([
                            DB::raw('users.*'),
                            DB::raw('user_projects.role'),
                            DB::raw('user_projects.id AS user_project_id')
                        ])
                      ->join('user_projects','user_projects.user_id','=','users.id')
                      ->where('user_projects.project_id','=',$this->id)
                      ->whereIn('user_projects.role', $roles)
                      ->get();
	}

	public function getAvgNote(){
		return ProjectNote::where('project_id', '=', $this->id)->avg('note');
	}

	public function getTotalNotes(){
		return ProjectNote::where('project_id', '=', $this->id)->count();	
	}
	
}