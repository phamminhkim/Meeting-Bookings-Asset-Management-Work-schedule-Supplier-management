<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignkeyTablePriceProductsAndPriceSpecs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_specs',function($table){
            $table->foreign('price_product_id')->references('id')->on('price_products')->onDelete('cascade');
           });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_specs',function($table){
            $table->dropForeign('price_specs_price_product_id_foreign');
        });
    }
}
