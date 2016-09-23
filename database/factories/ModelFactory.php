<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(\App\Models\DB\User::class, function (Faker\Generator $faker) {
    return [
        'name'           => $faker->name,
        'email'          => $faker->safeEmail,
        'password'       => bcrypt(123456),
        'remember_token' => str_random(10),
    ];
});

$factory->define(\App\Models\DB\Project::class, function (Faker\Generator $faker) {
    $categories = \App\Models\DB\Category::all();
    $categoryId = null;
    if($categories->count()){
       $category = $categories->random(1);
       $categoryId = $category->id;
    }
	return [
		'title'       => $faker->sentence(3, true),
		'description' => $faker->paragraph(20, true),
        'category_id' => $categoryId
	];
});

$factory->define(\App\Models\DB\Work::class, function (Faker\Generator $faker) {

    $today = new \DateTime();
    $startDate = $faker->dateTimeThisDecade($today);
    $endDate = $faker->dateTimeBetween($startDate,$today);

    return [
        'title'       => $faker->jobTitle, 
        'company'     => $faker->company, 
        'description' => $faker->paragraph(),
        'order'       => 1,
        'started_at'  => $startDate, 
        'ended_at'    => $endDate
    ];

});

$factory->define(\App\Models\DB\Graduation::class, function (Faker\Generator $faker) {
    $cursos = [ 
        "Direito","Análise e Desenvolvimento de Sistemas","Sistemas de Informação",
        "Ciência da Computação","Gastronomia","Engenharia Civil", "Física", "Economia", 
        "Biologia","História","Hotelaria"
    ];
    
    $cursosCollection = new Illuminate\Support\Collection($cursos);
    $curso = $cursosCollection->random(1);

    return [
        'course'        => $curso,
        'institution'   => "Universidade ".$faker->company." ".$faker->state,
        'conclusion_at' => $faker->dateTimeBetween('-5 years', '+5 years'),
        'order'         => 1
    ];

});
