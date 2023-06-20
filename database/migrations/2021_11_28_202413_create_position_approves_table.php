<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionApprovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('position_approves', function (Blueprint $table) {
            $table->id();
			$table->bigInteger('user_id')->unsigned();
			$table->unsignedBigInteger('position_id')->unsigned();
			$table->string('mask',1);
            $table->string('description')->nullable();
			$table->string('active',1);
            $table->timestamps();
        });
		 Schema::table('position_approves', function (Blueprint $table) {
			 $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('position_approves', function (Blueprint $table) {
			 $table->dropForeign('position_id')->references('id')->on('positions')->onDelete('cascade');
        });
		Schema::dropIfExists('position_approves');
    }
}
