<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrproductivityMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrproductivity_marks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('hrproductivity_staff_id')->unsigned();
            $table->integer('day')->nullable();
            $table->float('value',18)->nullable();
            $table->string('type_rank',20)->nullable();
            $table->dateTime('date')->nullable();
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
        Schema::dropIfExists('hrproductivity_marks');
    }
}
