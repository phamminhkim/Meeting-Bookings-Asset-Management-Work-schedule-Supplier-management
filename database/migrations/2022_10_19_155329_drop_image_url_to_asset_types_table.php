<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropImageUrlToAssetTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('asset_types','image_url')) {
            Schema::table('asset_types', function (Blueprint $table) {
                $table->dropColumn('image_url');
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('asset_types','image_url')) {
            Schema::table('asset_types', function (Blueprint $table) {
                $table->String('image_url')->nullable();
            });
        }
       
    }
}
