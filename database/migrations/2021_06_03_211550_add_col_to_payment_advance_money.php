<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColToPaymentAdvanceMoney extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_advance_money', function (Blueprint $table) {
            $table->string('serial_num',14)->nullable();
            $table->dateTime('serial_date',0)->nullable();
            $table->float('amount',18,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_advance_money', function (Blueprint $table) {
            $table->dropColumn('serial_num');
            $table->dropColumn('serial_date');
            $table->dropColumn('amount');
        });
    }
}
