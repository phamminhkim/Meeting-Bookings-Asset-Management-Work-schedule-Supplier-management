<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWlworkflowJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wlworkflow_jobs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('wlworkflow_status_id')->unsigned();
            $table->bigInteger('action_user')->unsigned();
            $table->string('job_name');
            $table->integer('finished')->nullable();
            $table->dateTime('finished_date')->nullable();
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
        Schema::dropIfExists('wlworkflow_jobs');
    }
}
