<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignkeyTablePriceReqsAndPriceProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_products',function($table){
            $table->foreign('price_req_id')->references('id')->on('price_reqs')->onDelete('cascade');
           });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_products',function($table){
            $table->dropForeign('price_products_price_req_id_foreign');
        });
    }
}
