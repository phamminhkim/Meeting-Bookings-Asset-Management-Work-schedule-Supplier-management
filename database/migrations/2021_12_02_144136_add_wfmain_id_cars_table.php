<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWfmainIdCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('cars', function($table) {
		  $table->bigInteger('wfmain_id')->unsigned();
		  $table->bigInteger('wfmainconfig_id')->nullable()->unsigned();
		 });
		 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('cars', function($table) {
		  $table->dropColumn('wfmain_id');
		   $table->dropColumn('wfmainconfig_id');
		 });
    }
}
