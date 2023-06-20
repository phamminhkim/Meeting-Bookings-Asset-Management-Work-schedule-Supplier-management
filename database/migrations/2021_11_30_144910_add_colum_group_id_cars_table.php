<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumGroupIdCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
		//  Schema::table('cars', function($table) {
		//  $table->dropColumn('group_id');
		// });
		// Schema::table('cars', function (Blueprint $table) {
		// 	 $table->unsignedBigInteger('group_id')->unsigned();
		// 	 $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
