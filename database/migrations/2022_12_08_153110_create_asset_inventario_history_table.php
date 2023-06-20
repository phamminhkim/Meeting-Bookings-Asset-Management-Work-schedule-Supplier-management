<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetInventarioHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_inventario_history', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('asset_inventario_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('objectable_id')->nullable();
            $table->string('objectable_type')->nullable();
            $table->bigInteger('asset_status_id')->nullable();
            $table->bigInteger('user_confirm_status')->nullable();
            $table->bigInteger('stocker_confirm_status')->nullable();
            $table->integer('user_confirm_quantity')->nullable();
            $table->integer('stocker_confirm_quantity')->nullable();
            $table->bigInteger('quantity_use')->nullable();
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('asset_inventario_history');
    }
}
