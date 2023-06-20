<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdđColPaycatsetPaycattypeTablePaymentAttached extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_attacheds', function (Blueprint $table) {
            $table->bigInteger('paycatset_id')->unsigned()->nullable();
            $table->bigInteger('paycattype_id')->unsigned()->nullable();
        });		
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_attacheds', function (Blueprint $table) {

            $table->dropColumn('paycatset_id');
            $table->dropColumn('paycattype_id');

        });		
    }
}
