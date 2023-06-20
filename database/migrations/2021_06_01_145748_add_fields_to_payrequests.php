<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToPayrequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payrequests', function (Blueprint $table) {
            $table->float('amount_exchanged',18,2)->default(0);
            $table->float('exchange_rate',18,2)->default(0);
            $table->string('budget_num',50)->nullable();
            $table->string('money_receiver',150)->nullable();
            $table->string('doc_reference',50)->nullable();
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payrequests', function (Blueprint $table) {
            //
        });
    }
}
