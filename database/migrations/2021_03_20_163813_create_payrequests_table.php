<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payrequests', function (Blueprint $table) {
            $table->id();
            $table->float('amount',18);
            $table->string('content');
            $table->integer('status')->nullable();
            $table->date('finish_date')->nullable();
            $table->bigInteger('proposer_payment')->unsigned();
            $table->bigInteger('payrequest_type_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('bugget_id')->unsigned()->nullable();
            $table->bigInteger('team_id')->unsigned();
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('method_payment')->unsigned();
            $table->bigInteger('bank_id')->unsigned()->nullable();
            $table->string('bank_account',50)->nullable();
            $table->bigInteger('contract_id')->unsigned()->nullable();
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
        Schema::dropIfExists('payrequests');
    }
}
