<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWlworkflowParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wlworkflow_params', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('wlworkflow_status_id')->unsigned();
            $table->integer('wldatatype_id');
            $table->integer('require')->default(0);
            $table->string('description')->nullable();
            $table->integer('index_after_by')->nullable();
            $table->string('name');
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
        Schema::dropIfExists('wlworkflow_params');
    }
}
