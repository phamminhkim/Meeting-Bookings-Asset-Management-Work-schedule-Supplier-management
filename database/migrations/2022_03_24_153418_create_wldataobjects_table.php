<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWldataobjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wldataobjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('wlphase_id')->unsigned();
            $table->bigInteger('wldatatype_id')->unsigned();
            $table->integer('require');
            $table->string('description');
            $table->bigInteger('index_after_by')->nullable();

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
        Schema::dropIfExists('wldataobjects');
    }
}
