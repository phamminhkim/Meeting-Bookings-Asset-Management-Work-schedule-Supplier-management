<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('price_product_id')->unsigned();
            $table->bigInteger('vendor_id')->unsigned()->nullable();
            $table->float('quantity',18,2)->nullable();
            $table->float('price_old',18,2)->nullable();
            $table->float('price',18,2)->nullable();
            $table->integer('choose')->nullable();
            $table->string('brand',50)->nullable();
            $table->boolean('is_vat')->default(0);
            $table->string('vendor_display',50)->nullable();
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
        Schema::dropIfExists('price_details');
    }
}
