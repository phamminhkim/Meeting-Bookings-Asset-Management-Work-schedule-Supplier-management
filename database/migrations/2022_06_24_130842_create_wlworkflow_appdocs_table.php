<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWlworkflowAppdocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wlworkflow_appdocs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('objectable_id')->unsigned()->nullable();
            $table->string('objectable_type')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('document_type_id')->unsigned();
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('serial_num',14)->nullable();
            $table->integer('status')->nullable();
            $table->integer('finished')->nullable();
            $table->integer('locked')->nullable();
            $table->string('company_id',4);
            $table->bigInteger('department_id')->unsigned();
            $table->bigInteger('group_id')->unsigned();
            $table->bigInteger('team_id')->unsigned()->nullable();
            $table->bigInteger('teamconfig_id')->unsigned()->nullable();
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
        Schema::dropIfExists('wlworkflow_appdocs');
    }
}
