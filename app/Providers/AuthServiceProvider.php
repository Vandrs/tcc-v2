<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Enums\EnumCapabilities;
use App\Models\Enums\EnumProject;
use App\Models\Interfaces\C3Project;
use App\Models\Business\ProjectFollowerBusiness;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $gate->define(EnumCapabilities::UPDATE_PROJECT,function($user, C3Project $project){
            $exists = $project->getMembers()
                              ->where('id', $user->id)
                              ->first();
            if($exists){
                return true;
            }
            return false;
        });

        $gate->define(EnumCapabilities::DELETE_PROJECT,function($user, C3Project $project){
            $exists = $project->getMembers()
                              ->where('id', $user->id)
                              ->where('role', EnumProject::ROLE_OWNER, false)
                              ->first();
            if($exists){
                return true;
            }
            return false;
        });

        $gate->define(EnumCapabilities::FOLLOW_PROJECT,function($user, C3Project $project){
            $isMember = $project->getMembers()
                                ->where('id', $user->id)
                                ->first();
            $isFollower = ProjectFollowerBusiness::isUserFollowingProject($user, $project);
            if(empty($isMember) && empty($isFollower)){
                return true;
            }
            return false;
        });

        $gate->define(EnumCapabilities::MAKE_POST_PROJECT, function($user, C3Project $project){
            $exists = $project->getMembers()
                              ->where('id', $user->id)
                              ->whereIn('role',[EnumProject::ROLE_OWNER, EnumProject::ROLE_CONTRIBUTOR], false)
                              ->first();
            if($exists){
                return true;
            }
            return false;
        });
        
    }
}
