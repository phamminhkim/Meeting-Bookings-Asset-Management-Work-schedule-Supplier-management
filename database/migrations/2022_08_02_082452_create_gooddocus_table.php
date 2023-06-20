<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGooddocusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gooddocus', function (Blueprint $table) {
            $table->id();
            $table->integer('sloc_id');
            $table->string('serial_num')->nullable();
            $table->string('shipper_name')->nullable();;
            $table->integer('user_id');
            $table->integer('type');
            $table->string('reason')->nullable();;
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
        Schema::dropIfExists('gooddocus');
    }
}
