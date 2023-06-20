<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceSpecitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_specitems', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('price_product_id')->unsigned();
            $table->bigInteger('price_spec_id')->unsigned();
            $table->bigInteger('vendor_id')->unsigned()->nullable();
            $table->string('vendor_display',50)->nullable();
            $table->string('value',50)->nullable();
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
        Schema::dropIfExists('price_specitems');
    }
}
