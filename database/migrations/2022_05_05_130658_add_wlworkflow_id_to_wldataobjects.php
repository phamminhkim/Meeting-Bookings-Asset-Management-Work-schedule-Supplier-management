<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWlworkflowIdToWldataobjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wldataobjects', function (Blueprint $table) {
            $table->bigInteger('wlworkflow_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wldataobjects', function (Blueprint $table) {
            $table->dropColumn('wlworkflow_id');
        });
    }
}
