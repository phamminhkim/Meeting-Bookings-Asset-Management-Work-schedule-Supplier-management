<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGooddocusDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gooddocus_details', function (Blueprint $table) {
            $table->id();
            $table->integer('gooddocu_id');
            $table->integer('good_id');
            $table->integer('goodunit_id');
            $table->float('quantity');
            $table->float('unit_price')->nullable();
            $table->float('amount')->nullable();
            $table->datetime('end_date')->nullable();
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
        Schema::dropIfExists('gooddocus_details');
    }
}
