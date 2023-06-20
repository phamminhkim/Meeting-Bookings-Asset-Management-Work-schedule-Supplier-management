<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTermPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_term_plans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('contract_term_id')->unsigned();
            $table->bigInteger('contract_id')->unsigned();
            $table->integer('terms_num');
            $table->string('content');
            $table->date('date_due');
            $table->float('amount',18);
            $table->date('date_paid')->nullable();
            $table->float('amount_paid',18)->nullable();
            $table->string('reference_num',50)->nullable();
            $table->integer('status')->nullable();
            $table->integer('user_id');
            $table->string('note',100)->nullable();
            $table->string('term_content')->nullable();
           
            $table->bigInteger('sub_contract_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_term_plans');
    }
}
