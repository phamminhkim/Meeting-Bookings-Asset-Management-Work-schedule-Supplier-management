<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWfstepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wfsteps', function (Blueprint $table) {
            $table->id();
            $table->string('wfmain_id',4)  ;
            $table->bigInteger('next_user')->unsigned()->nullable();
            $table->string('name',100)  ;
            $table->string('description')  ;
            $table->integer('step')  ;
            $table->string('form_approval')  ;
            $table->boolean('is_end');
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
        Schema::dropIfExists('wfsteps');
    }
}
