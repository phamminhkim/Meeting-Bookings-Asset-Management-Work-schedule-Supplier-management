<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWljobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wljobs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('time_execution')->nullable();
            $table->integer('scores')->nullable();
            $table->bigInteger('action_user');
            $table->bigInteger('wlphase_id');
            $table->string('description')->nullable();
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
        Schema::dropIfExists('wljobs');
    }
}
