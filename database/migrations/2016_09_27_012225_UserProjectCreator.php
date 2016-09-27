<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserProjectCreator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_projects', function (Blueprint $table) {
            $table->boolean('creator')->default(0)->after('role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_projects', function (Blueprint $table) {
            $table->dropColumn('creator');
        });
    }
}
