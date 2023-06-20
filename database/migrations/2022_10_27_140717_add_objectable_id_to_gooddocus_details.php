<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddObjectableIdToGooddocusDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gooddocus_details', function (Blueprint $table) {
            $table->bigInteger('objectable_id')->unsigned();
            $table->string('objectable_type');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gooddocus_details', function (Blueprint $table) {
            $table->dropColumn('objectable_id');
            $table->dropColumn('objectable_type');
        });
    }
}
