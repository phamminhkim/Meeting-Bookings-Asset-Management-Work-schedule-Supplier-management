<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('payment_contracts', function (Blueprint $table) {
        //     $table->id();
        //     $table->bigInteger('payrequest_id')->unsigned();
        //     $table->bigInteger('contract_term_id')->unsigned();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('payment_contracts');
    }
}
