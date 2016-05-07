<?php

use Illuminate\Database\Seeder;
use App\Models\DB\Project;
use App\Models\DB\User;
use App\Models\DB\UserProject;
use App\Models\Enums\EnumProject;

class UserProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$users = User::query()->get();
    	if($users->count() == 0){
    		throw new \Exception('Nenhum usuário encontrado');
    	}
        $projectsWithNoOwnder = Project::whereNotExists(function($query){
        	$query->select(DB::raw(1))
        		  ->from('user_projects')
        		  ->where('user_projects.project_id','=',DB::raw('projects.id'));
        })->get();
        $projectsWithNoOwnder->each(function($project) use ($users){
        	$user = $users->random(1);
        	UserProject::create([
        		'user_id' 	 => $user->id,
        		'project_id' => $project->id,
        		'role' 		 => EnumProject::ROLE_OWNER
        	]);
        });
     	$this->command->info($projectsWithNoOwnder->count().' projetos foram relacionados aos usuários');
    }
}
