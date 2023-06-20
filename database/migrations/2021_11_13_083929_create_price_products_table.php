<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('price_req_id')->unsigned();
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->string('code_sap',18)->nullable();
            $table->string('name',100);
            $table->string('unit',30);

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
        Schema::dropIfExists('price_products');
    }
}
