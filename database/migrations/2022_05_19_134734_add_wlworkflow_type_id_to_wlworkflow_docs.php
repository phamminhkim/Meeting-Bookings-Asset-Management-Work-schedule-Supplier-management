<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWlworkflowTypeIdToWlworkflowDocs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wlworkflow_docs', function (Blueprint $table) {
            $table->bigInteger('wlworkflow_type_id')->unsigned();
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
            $table->dropColumn('wlworkflow_type_id');
        });
    }
}
