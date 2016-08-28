<?php 

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\DB\ProjectNote;
use App\Models\DB\Category;
use App\Models\DB\User;
use App\Models\DB\Image;
use App\Models\DB\File;
use App\Models\DB\Post;
use App\Models\Enums\EnumProject;
use App\Models\Interfaces\C3Project;
use App\Utils\StringUtil;
use DB;

class Project extends Model implements C3Project{

	use SoftDeletes;

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
		foreach($this->images->all() as $image){
			if($image->cover == 1){
				return $image;
			}
		}
		return $this->images->first();
	}

	public function getMembers(){
		$roles = [EnumProject::ROLE_CONTRIBUTOR, EnumProject::ROLE_OWNER, EnumProject::ROLE_MENTOR];
		return $this->getProjectUsers($roles);
	}

	public function getFollowers(){
		$roles = [EnumProject::ROLE_FOLLOWER];
		return $this->getProjectUsers($roles);
	}

	private function getProjectUsers($roles = []){
		$baseQuery  = User::select([
                    	DB::raw('users.*'),
                    	DB::raw('user_projects.role'),
                    	DB::raw('user_projects.id AS user_project_id')
                      ])		
                      ->join('user_projects','user_projects.user_id','=','users.id')
                      ->where('user_projects.project_id','=',$this->id);
        if(!empty($roles)){
        	$baseQuery->whereIn('user_projects.role', $roles);
        }                      
        return $baseQuery->orderBy('name','ASC')->get();
	}

	public function getAvgNote(){
		return number_format(ProjectNote::where('project_id', '=', $this->id)->avg('note'),2);
	}

	public function getTotalNotes(){
		return ProjectNote::where('project_id', '=', $this->id)->count();	
	}

	public function posts(){
		return $this->hasMany(Post::class);
	}

	public function getPosts(){
		return $this->posts;
	}

	public function isMember($user){
		return $this->getMembers()->where('id',$user->id,false)->first();
	}

	public function getUrlAttribute($value = null){
		$path = StringUtil::toUrl($this->title."-".$this->id);
		return route('site.project.view',['path' => $path]);
	}
	
}