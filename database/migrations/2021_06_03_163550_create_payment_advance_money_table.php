<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentAdvanceMoneyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_advance_money', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('payrequest_id')->unsigned();
            $table->bigInteger('advance_money_id')->unsigned();
            $table->boolean('finished')->default(0);
            
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
        Schema::dropIfExists('payment_advance_money');
    }
}
