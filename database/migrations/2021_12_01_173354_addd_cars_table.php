<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdddCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
			// Schema::create('cars', function (Blueprint $table) {
            // $table->id();
            // $table->integer('status')->nullable();
            // $table->dateTime('issue_date')->nullable();
            // $table->bigInteger('proposer')->unsigned();
            // $table->bigInteger('user_id')->unsigned();
            // $table->string('company_id',4);
            // $table->bigInteger('department_id')->unsigned();
            // $table->dateTime('send_date')->nullable();
            // $table->bigInteger('document_type_id')->unsigned();
            // $table->string('serial_num',100)->nullable();
            // $table->integer('locked')->nullable();
            // $table->text('content');
            // $table->integer('issue_count')->nullable();
            // $table->bigInteger('car_error_id')->nullable()->unsigned();
            // $table->string('cause')->nullable();
            // $table->string('risk')->nullable();
            // $table->bigInteger('group_id')->unsigned();
            // $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //  Schema::dropIfExists('cars');
    }
}
