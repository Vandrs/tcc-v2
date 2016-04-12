<?php

use Illuminate\Database\Seeder;
use App\Models\DB\User;
use App\Models\DB\Project;
use App\Models\DB\ProjectNote;
use App\Utils\Benchmark;

class ProjectNotesSeeder extends Seeder
{
	public $progressBar;
	public $minNote;
	public $maxNote;
	public $qtdProjectsToAval;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->qtdProjectsToAval = (int) $this->command->ask('Quantidade de projetos que cada usuário vai avaliar: ', 5);
        $this->minNote = (int) $this->command->ask('Nota mínima possível: ', 1);
        $this->maxNote = (int) $this->command->ask('Nota máxima possível: ', 10);
        $this->command->info('Realizando avaliações');

        $benchmarck = new Benchmark();
        $benchmarck->start();
        DB::statement("TRUNCATE TABLE project_notes");
        $user =  User::all();
        $projects = Project::all();

        $qtdAvaliacoes = $user->count() * $this->qtdProjectsToAval;
        $this->progressBar = $this->command->getOutput()->createProgressBar($qtdAvaliacoes);
        $this->makeEvaluations($user, $projects);
        $this->progressBar->finish();
        $elapsedTime = $benchmarck->stop()->elapsedSeconds() / 60;
        $this->command->info('');
        $this->command->info('Prcesso realizado em '.$elapsedTime.' minutos');
    }

    private function makeEvaluations($users, $projects){
    	$seeder = $this;
    	$users->each(function($user) use($projects, $seeder){
    		$projectsToAval = $projects->random($seeder->qtdProjectsToAval);
    		$projectsToAval->each(function($project) use($user, $seeder){
    			$this->progressBar->advance();
    			$note = rand($seeder->minNote,$seeder->maxNote);
    			ProjectNote::create(['user_id' => $user->id, 'project_id' => $project->id, 'note' => $note]);
    		});
    	});
    }
}
