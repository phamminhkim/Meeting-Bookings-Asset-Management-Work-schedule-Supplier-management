<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnWfstepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('wfsteps', function($table) {
		  $table->bigInteger('wfapptype_id')->unsigned();
		 });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('wfsteps', function($table) {
		  $table->dropColumn('wfapptype_id');
		 });
    }
}
