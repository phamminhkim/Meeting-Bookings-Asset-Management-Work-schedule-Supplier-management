<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColPriceDetailIdToPriceSpecitems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('price_specitems', function (Blueprint $table) {
        //     $table->bigInteger('price_detail_id')->unsigned()->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('price_specitems', function (Blueprint $table) {
        //     $table->dropColumn('price_detail_id');
        // });
    }
}
