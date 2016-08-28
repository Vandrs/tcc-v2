<?php

namespace App\Models\DB;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\DB\ProjectNote;
use App\Models\DB\Projects;
use App\Models\Enums\EnumProject;
use DB;

class User extends Authenticatable
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

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

}
