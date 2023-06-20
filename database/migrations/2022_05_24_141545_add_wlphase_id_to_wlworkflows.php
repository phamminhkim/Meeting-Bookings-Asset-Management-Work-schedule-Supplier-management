<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWlphaseIdToWlworkflows extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wlworkflows', function (Blueprint $table) {
            $table->bigInteger('current_phase')->unsigned()->nullable();
            $table->bigInteger('finished_phase')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wlworkflows', function (Blueprint $table) {
           $table->dropColumn('current_phase');
           $table->dropColumn('finished_phase');
        });
    }
}
