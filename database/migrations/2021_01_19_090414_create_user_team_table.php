<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_team', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('team_id')->unsigned();

        });

        Schema::table('user_team', function (Blueprint $table) {

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_team', function (Blueprint $table) {
            $table->dropForeign('user_team_user_id_foreign')->references('id')->on('users')->onDelete('cascade');
            $table->dropForeign('user_team_team_id_foreign')->references('id')->on('teams')->onDelete('cascade');
        });
        Schema::dropIfExists('user_team');
    }
}
