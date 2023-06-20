<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_team', function (Blueprint $table) {
            $table->bigInteger('role_id')->unsigned();
            $table->bigInteger('team_id')->unsigned();
        });

        Schema::table('role_team', function (Blueprint $table) {

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
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

        Schema::table('role_team', function (Blueprint $table) {
            $table->dropForeign('role_team_role_id_foreign')->references('id')->on('roles')->onDelete('cascade');
            $table->dropForeign('role_team_team_id_foreign')->references('id')->on('teams')->onDelete('cascade');
        });
        Schema::dropIfExists('role_team');
    }
}
