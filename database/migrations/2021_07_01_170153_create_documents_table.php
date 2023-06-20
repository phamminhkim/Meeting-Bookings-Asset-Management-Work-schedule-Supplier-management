<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title',50)->nullable();
            $table->text('content')->nullable();
            $table->float('amount',18,2)->default(0);
            $table->integer('budget_type')->nullable();
            $table->string('currency',3)->nullable();
            $table->string('serial_num',14)->nullable();
            $table->string('budget_num',50)->nullable();
            $table->integer('status')->nullable();
            $table->string('company_id',4);
            $table->bigInteger('department_id')->unsigned();
            $table->bigInteger('payment_type_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('document_type_id')->unsigned();
            $table->bigInteger('group_id')->unsigned()->nullable();
            $table->bigInteger('team_id')->unsigned()->nullable();
            $table->integer('finished')->nullable();
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
        Schema::dropIfExists('documents');
    }
}
