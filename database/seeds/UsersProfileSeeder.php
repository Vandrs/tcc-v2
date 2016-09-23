<?php

use Illuminate\Database\Seeder;

class UsersProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Criando Cursos');
        $usuarios = \App\Models\DB\User::whereNotIn('users.id',function($query){
        	$query->select(["user_id"])->distinct()->from("graduations");
        })->get();

        $this->command->info('Encontrados '.$usuarios->count().' usuários sem cursos');
        $command = $this->command;

        $usuarios->each(function($user) use ($command) {
        	
        	$command->info("Criando cursos para o usuário: ".$user->name);
        	$qtd = rand(1,3);
        	$command->info("Criando: ".$qtd." cursos");
        	
        	if($qtd == 1){
        		$curso = factory(\App\Models\DB\Graduation::class, $qtd)->make();
        		$curso->user_id = $user->id;
	        	$curso->save();
        	} else {
        		$cursos = factory(\App\Models\DB\Graduation::class, $qtd)->make();
        		$cursos->each(function($curso) use ($user, $command) {
	        		$curso->user_id = $user->id;
	        		$curso->save();
        		});
        	}
        	
        });

        $this->command->info('Cursos criados com sucesso');
    }
}
