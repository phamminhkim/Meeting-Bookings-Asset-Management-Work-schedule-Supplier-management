<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWlworkflowStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wlworkflow_statuses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('wlworkflow_doc_id')->unsigned();
            $table->bigInteger('wlphase_strategy_id')->unsigned();
            $table->integer('time_execution')->default(0);
            $table->integer('status')->nullable();
            $table->string('phase_name');
            $table->bigInteger('user_id')->unsigned();
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
        Schema::dropIfExists('wlworkflow_statuses');
    }
}
