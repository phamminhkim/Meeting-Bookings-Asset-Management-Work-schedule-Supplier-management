<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSapCodeToAssetToolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_tools', function (Blueprint $table) {
            $table->string('sap_code',50)->nullable();
            $table->bigInteger('department_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset_tools', function (Blueprint $table) {
            $table->dropColumn('sap_code');
            $table->dropColumn('department_id');
        });
    }
}
