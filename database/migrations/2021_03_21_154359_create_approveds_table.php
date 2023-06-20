<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approveds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('team_id')->unsigned();
            $table->integer('level')->nullable();
            $table->bigInteger('approvedable_id')->unsigned();
            $table->string('approvedable_type');
            $table->dateTime('checkin')->nullable();
            $table->dateTime('checkout')->nullable();
            $table->integer('release')->nullable();
            $table->string('note',150)->nullable();
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
        Schema::dropIfExists('approveds');
    }
}
