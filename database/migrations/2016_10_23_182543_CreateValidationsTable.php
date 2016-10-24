<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValidationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('validations', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('project_validation_id')->unsigned();
            $table->string('name',150);
            $table->string('email',150)->nullable()->default(null);
            $table->string('occupation',150);
            $table->string('gender',1);
            $table->smallInteger('age')->unsigned();
            $table->text('suggestion')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('project_validation_id')->references('id')->on('project_validations');
            $table->index('gender');
            $table->index('age');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('validations');
    }
}
