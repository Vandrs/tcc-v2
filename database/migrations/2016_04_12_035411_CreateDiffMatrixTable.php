<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiffMatrixTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diff_matrix', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_a')->unsigned();
            $table->integer('project_b')->unsigned();
            $table->float('diff');
            $table->foreign('project_a')->references('id')->on('projects');
            $table->foreign('project_b')->references('id')->on('projects');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('diff_matrix');
    }
}
