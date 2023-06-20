<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_tools', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('asset_type_id')->unsigned();
            $table->bigInteger('asset_warehouse_id')->unsigned();
            $table->string('name',150);
            $table->bigInteger('asset_group_id');
            $table->bigInteger('vendor_id')->unsigned();
            $table->string('hashtag')->nullable();
            $table->string('producer',150)->nullable();
            $table->float('amount',18,2)->nullable();
            $table->float('quantity')->nullable();
            $table->datetime('add_date')->nullable();
            $table->string('image_url')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('asset_tools');
    }
}
