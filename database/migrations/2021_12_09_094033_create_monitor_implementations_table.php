<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonitorImplementationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitor_implementations', function (Blueprint $table) {
            $table->id();
			$table->bigInteger('car_id')->unsigned();
			$table->bigInteger('cause_measure_id')->unsigned();
            $table->bigInteger('result');
			$table->dateTime('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monitor_implementations');
    }
}
