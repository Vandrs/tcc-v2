<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$qtd = $this->command->ask("Quantos usuários deseja criar?",1);
    	if($qtd && is_numeric($qtd)){
    		$this->command->info('Criando '.$qtd.' usuário(s)');
	        $users = factory(\App\Models\DB\User::class, (int)$qtd)->create();
	        foreach($users->all() as $index => $user){
	        	$this->command->info(($index+1).' usuario: '.$user->name);
	        }
    	} else {
    		$this->command->error("Informe um número inteiro");
    	}
    }
}
