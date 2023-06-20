<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalanderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_details', function (Blueprint $table) {
            $table->id();
            $table->integer('calendar_id');
            $table->string('month');
            $table->string('week');
            $table->date('date')->nullable();
            $table->longText('work');
            $table->longText('calendar_holiday_id')->nullalble();
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
        Schema::dropIfExists('calendar_details');
    }
}
