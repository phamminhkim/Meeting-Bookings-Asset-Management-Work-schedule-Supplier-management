<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWfapprovedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wfapproveds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('team_id')->unsigned();
            $table->integer('level')->nullable();
            $table->bigInteger('wfapprovedable_id')->unsigned();
            $table->string('wfapprovedable_type');
            $table->dateTime('checkin')->nullable();
            $table->dateTime('checkout')->nullable();
            $table->integer('release')->nullable();
            $table->string('note')->nullable();
            $table->integer('version')->default(1);
            $table->string('online',1)->default('X');
            $table->integer('step')->default(1);
            $table->integer('finished')->default(0);
            $table->integer('sign')->nullable();
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
        Schema::dropIfExists('wfapproveds');
    }
}
