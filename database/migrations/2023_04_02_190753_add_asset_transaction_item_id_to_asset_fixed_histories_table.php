<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssetTransactionItemIdToAssetFixedHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_fixed_histories', function (Blueprint $table) {
            $table->bigInteger('asset_transaction_item_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset_fixed_histories', function (Blueprint $table) {
            $table->dropColumn('asset_transaction_item_id');
        });
    }
}
