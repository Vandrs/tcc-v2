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

$factory->define(\App\Models\DB\Project::class, function (Faker\Generator $faker){
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
