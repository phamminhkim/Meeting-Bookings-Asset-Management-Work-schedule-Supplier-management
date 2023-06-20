<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWlworkflowDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wlworkflow_docs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('wlworkflow_id')->unsigned()->nullable();
            $table->bigInteger('wlworkflow_status_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('document_type_id')->unsigned();
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('serial_num',14)->nullable();
            $table->integer('status')->nullable();
            $table->integer('finished')->nullable();
            $table->bigInteger('objectable_id')->unsigned()->nullable();
            $table->string('objectable_type')->nullable();
            $table->string('company_id',4);
            $table->bigInteger('department_id')->unsigned();
            $table->bigInteger('group_id')->unsigned();
            $table->bigInteger('team_id')->unsigned()->nullable();
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
        Schema::dropIfExists('wlworkflow_docs');
    }
}
