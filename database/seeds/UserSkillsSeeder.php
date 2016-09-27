<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use App\Models\DB\User;

class UserSkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Iniciando criação de skills para os usuários');
        $users = User::query()->whereNull('skills');
        
        $this->command->info('Atribuindo skills para '.$users->count().' usuários');
        $skills = $this->getSkillsCollection();
        $users->each(function($user) use ($skills) {
        	$qtd = rand(4,10);
        	$items = $skills->random($qtd)->values();
        	$user->update(['skills' => $items]);
        });
    }

    public function getSkillsCollection()
    {
    	$skills = [
    		"Oracle","Mysql","SqlServer","MongoDB","Redis","Memcached","Postgres",
    		"PHP","Java","Ruby","Python","C#",".NET","Scala","NodeJS","JavaScript",
    		"Jquery","AngularJS","KnockoutJS","ReactJS","VueJs","EmberJs",
    		"Docker","Vagrant","Amazon AWS","Dev Ops","Full Stack Developer",
    		"Laravel","Rails","Django","Jsf","Jsp","Yii","Symphony","Beanstalkd",
    		"SEO","Analytics","HTML","HTML5","CSS","CSS 3","Unity","Unreal Engine","Game Maker",
    		"Scrum","Agile","XP","Lean","Métodos Ágeis","Elasticsearch", "Solr", "Clojure"
    	];
    	return new Collection($skills);
    }
}
