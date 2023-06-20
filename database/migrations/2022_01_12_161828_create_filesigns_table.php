<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filesigns', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('objectable_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->string('objectable_type');
            $table->string('signed',1)->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('filesigns');
    }
}
