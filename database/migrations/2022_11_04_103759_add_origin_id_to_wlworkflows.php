<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOriginIdToWlworkflows extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wlworkflows', function (Blueprint $table) {
            $table->bigInteger('original_id')->nullable()->after('id');
        });
        Schema::table('wlworkflow_docs', function (Blueprint $table) {
            $table->bigInteger('original_id')->nullable()->after('wlworkflow_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wlworkflows', function (Blueprint $table) {
            $table->dropColumn('original_id');
        });
        Schema::table('wlworkflow_docs', function (Blueprint $table) {
            $table->dropColumn('original_id');
        });
    }
}
