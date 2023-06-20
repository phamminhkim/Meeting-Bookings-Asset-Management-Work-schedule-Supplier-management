<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssetdableIdAndAssetdableTypeToAssetTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_transactions', function (Blueprint $table) {
            $table->bigInteger('assetdable_id')->nullable();
            $table->string('assetdable_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset_transactions', function (Blueprint $table) {
            $table->dropColumn('assetdable_id');
            $table->dropColumn('assetdable_type');
        });
    }
}
