<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuanttityStockToAssetTransactionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_transaction_items', function (Blueprint $table) {
            $table->bigInteger('quantity_sloc')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset_transaction_items', function (Blueprint $table) {
            $table->dropColumn('quantity_sloc');

        });
    }
}
