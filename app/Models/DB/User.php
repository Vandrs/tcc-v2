<?php

namespace App\Models\DB;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\DB\ProjectNote;
use App\Models\DB\Projects;
use App\Models\DB\Work;
use App\Models\DB\Graduation;
use App\Models\Enums\EnumProject;
use App\Models\Interfaces\C3User;
use DB;

class User extends Authenticatable implements C3User
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'skills', 'in_elastic'
    ];

    protected $casts = [
      'skills' => 'array'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function boot(){
        parent::boot();
        User::saving(function(User $user){
            $user->in_elastic = 0;
        });
    }

    public function notes(){
        return $this->hasMany(ProjectNote::class);
    }

    public function qtdEvaluatedProjects(){
        return ProjectNote::where('user_id','=',$this->id)->count();
    }

    public function projectsAsOwner(){
        return $this->baseQueryProjecs()->where('user_projects.role','=',EnumProject::ROLE_OWNER)->get();
    }

    private function baseQueryProjecs(){
        return Project::select([
                            DB::raw('projects.*'),
                            DB::raw('user_projects.role'),
                            DB::raw('user_projects.id AS user_project_id')
                        ])
                      ->join('user_projects','user_projects.project_id','=','projects.id')
                      ->where('user_projects.user_id','=',$this->id);
    }

    public function graduations(){
      return $this->hasMany(Graduation::class);
    }

    public function works(){
      return $this->hasMany(Work::class);
    }

    public function getCurrentOrLastWork()
  {
    if ($this->works->count() == 0) {
      return null;
    }

    $currentWorks = $this->works->filter(function ($work) {
      return is_null($work->ended_at) ? true : false;
    });

    if ($currentWorks->count()) {
      return $currentWorks->sortBy("title")->first();
    } else {
      return $this->works->sortByDesc("ended_at")->first();
    }
  }

  public function getCurrentOrLastGraduation()
  {
    if ($this->graduations->count() == 0) {
      return null;
    }
    return $this->graduations->sortByDesc("ended_at")->first();
  }

}
