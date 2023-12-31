<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWfmainconfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wfmainconfigs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('wfmainconfigable_id')->unsigned();
            $table->string('wfmainconfigable_type');
            $table->string('name',100)  ;
            $table->string('description')  ;
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
        Schema::dropIfExists('wfmainconfigs');
    }
}
