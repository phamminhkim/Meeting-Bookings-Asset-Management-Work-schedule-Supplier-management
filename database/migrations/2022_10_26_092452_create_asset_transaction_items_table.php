<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetTransactionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_transaction_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('asset_transaction_id');
            $table->bigInteger('objectable_id');
            $table->string('objectable_type');
            $table->integer('quantity');
            $table->bigInteger('asset_status_id')->nullable();
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
        Schema::dropIfExists('asset_transaction_items');
    }
}
