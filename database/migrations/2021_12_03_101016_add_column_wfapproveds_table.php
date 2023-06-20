<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnWfapprovedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('wfapproveds', function($table) {
		  $table->bigInteger('wfapptype_id')->unsigned();
		  $table->bigInteger('wfusertype_id')->nullable()->unsigned();
		 });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('wfapproveds', function($table) {
		  $table->dropColumn('wfapptype_id');
		   $table->dropColumn('wfusertype_id');
		 });
    }
}
