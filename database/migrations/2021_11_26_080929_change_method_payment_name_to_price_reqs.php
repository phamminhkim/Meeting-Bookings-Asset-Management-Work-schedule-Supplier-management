<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMethodPaymentNameToPriceReqs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_reqs', function (Blueprint $table) {
            $table->string('method_payment_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_reqs', function (Blueprint $table) {

            $table->string('method_payment_name',50)->nullable()->change();
        });
    }
}
