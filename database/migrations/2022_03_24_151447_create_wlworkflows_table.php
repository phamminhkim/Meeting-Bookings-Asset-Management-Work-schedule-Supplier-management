<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWlworkflowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wlworkflows', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('wlworkflow_type_id')->unsigned();
            $table->string('description')->nullable();
            $table->integer('wloverdue_id')->unsigned();
            $table->integer('rule_change_phase')->unsigned();
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
        Schema::dropIfExists('wlworkflows');
    }
}
