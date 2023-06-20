<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFieldsToWlworkflowDocs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wlworkflow_docs', function (Blueprint $table) {
            $table->dropColumn('wlworkflow_status_id');
            $table->dropColumn('content');
            $table->dropColumn('teamconfig_id');
            $table->dropColumn('objectable_id');
            $table->dropColumn('objectable_type');
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wlworkflow_docs', function (Blueprint $table) {
            //
        });
    }
}
