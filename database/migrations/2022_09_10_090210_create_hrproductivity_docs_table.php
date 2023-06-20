<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrproductivityDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrproductivity_docs', function (Blueprint $table) {
            $table->id();
            $table->string('company_id',4);
            $table->bigInteger('department_id')->unsigned();
            $table->bigInteger('document_type_id')->unsigned();
            $table->bigInteger('workshop_id')->unsigned();
            $table->bigInteger('party_id')->unsigned();
            $table->integer('year');
            $table->integer('month');
            $table->bigInteger('group_id')->unsigned()->nullable();
            $table->bigInteger('team_id')->unsigned()->nullable();
            $table->bigInteger('teamconfig_id')->unsigned()->nullable();
            $table->integer('status')->nullable();
            $table->dateTime('send_date')->nullable();
            $table->string('serial_num',14)->nullable();
            $table->integer('locked')->default(0);
            $table->integer('printed')->default(0);
            $table->integer('productivity_type')->default(0);
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
        Schema::dropIfExists('hrproductivity_docs');
    }
}
