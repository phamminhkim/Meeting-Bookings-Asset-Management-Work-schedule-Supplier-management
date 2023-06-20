<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeamconfigIdToPriceReqs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_reqs', function (Blueprint $table) {
            $table->bigInteger('teamconfig_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_reqs', function (Blueprint $table) {
            $table->dropColumn('teamconfig_id');
        });
    }
}
