<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->bigInteger('create_by')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->integer('phone')->nullable();
            $table->string('address')->nullable();
            $table->integer('active')->default(1);
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
        Schema::dropIfExists('asset_warehouses');
    }
}
