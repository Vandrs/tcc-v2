<?php

use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $qtd = $this->command->ask("Quantos projetos deseja criar?",1);
    	if($qtd && is_numeric($qtd)){
    		$this->command->info('Criando '.$qtd.' projetos(s)');
	        $projetos = factory(\App\Models\DB\Project::class, (int)$qtd)->create();
	        foreach($projetos->all() as $index => $projeto){
	        	$this->command->info(($index+1).' projeto: '. $projeto->title);
	        }
    	} else {
    		$this->command->error("Informe um número inteiro");
    	}
    }
}
