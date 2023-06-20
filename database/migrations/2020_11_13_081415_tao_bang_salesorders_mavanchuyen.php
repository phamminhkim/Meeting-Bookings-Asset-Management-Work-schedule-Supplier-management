<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TaoBangSalesordersMavanchuyen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmdt_saleorders_mavanchuyen', function (Blueprint $table) {
            $table->increments('id');
            $table->string('madonhang', 10);
            $table->string('mavanchuyen', 100);
            $table->bigInteger('userid');
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
        Schema::dropIfExists('tmdt_saleorders_mavanchuyen');
    }
}
