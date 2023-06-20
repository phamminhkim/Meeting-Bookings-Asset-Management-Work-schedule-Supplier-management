<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnCarerrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		 Schema::table('car_errors', function($table) {
		 $table->dropForeign(['department_id']);
		 $table->dropColumn('department_id');		
		 });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		 Schema::table('car_errors', function($table) {
		  $table->bigInteger('department_id')->unsigned();;
		  $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
		});
    }
}
