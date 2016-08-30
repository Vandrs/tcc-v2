<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProjectManagement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('trello_board_id', 100)->nullable()->default(null)->after('urls');
            $table->string('trello_account', 100)->nullable()->default(null)->after('trello_board_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('trello_board_id');
            $table->dropColumn('trello_account');
        });
    }
}
