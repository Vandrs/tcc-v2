<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserSocialLogin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('photo')->nullable()->default(null)->after('skills');
            $table->string('gender', 1)->nullable()->default(null)->after('photo');
            $table->dateTime('birth_date')->nullable()->default(null)->after('gender');
            $table->string('social_id')->nullable()->default(null)->after('trello_token');
            $table->string('social_driver',4)->nullable()->default(null)->after('social_id');
            $table->string('password',255)->nullable()->default(null)->change();
            $table->index('social_id');
            $table->index('social_driver');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_social_id_index');
            $table->dropIndex('users_social_driver_index');
            $table->dropColumn('photo');
            $table->dropColumn('social_id');
            $table->dropColumn('social_driver');
            $table->dropColumn('birth_date');
            $table->dropColumn('gender');
            $table->string('password',255)->nullable()->default(null)->change();
        });
    }
}
