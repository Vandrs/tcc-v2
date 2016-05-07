<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',50);
            $table->timestamps();
        });

        $data = [
            ['name' => 'Arte'],
            ['name' => 'Quadrinhos'],
            ['name' => 'Design'],
            ['name' => 'Moda'],
            ['name' => 'Cinema e vídeo'],
            ['name' => 'Culinária'],
            ['name' => 'Games'],
            ['name' => 'Musica'],
            ['name' => 'Fotografia'],
            ['name' => 'Publicidade'],
            ['name' => 'Tecnologia'],
            ['name' => 'Outros']
        ];

        DB::table('categories')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('categories');
    }
}
