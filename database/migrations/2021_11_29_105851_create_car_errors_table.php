<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarErrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('car_errors', function (Blueprint $table) {
            $table->id();
			$table->string('name');
			$table->unsignedBigInteger('department_id')->unsigned();
			$table->string('description')->nullable();
			$table->string('active',1);
            $table->timestamps();
        });
		Schema::table('car_errors', function (Blueprint $table) {
			 $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_errors', function (Blueprint $table) {
			 $table->dropForeign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
		Schema::dropIfExists('car_errors');
    }
}
