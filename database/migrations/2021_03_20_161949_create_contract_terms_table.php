<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_terms', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('contract_id')->unsigned();
            $table->integer('terms_num');
            $table->string('content');
            $table->date('date_due');
            $table->float('amount',18);
            $table->date('date_paid')->nullable();
            $table->float('amount_paid',18)->nullable();
            $table->string('reference_num',50);
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('contract_terms');
    }
}
