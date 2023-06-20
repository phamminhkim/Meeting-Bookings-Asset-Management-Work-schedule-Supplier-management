<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCanBeCreatedToWlworkflowJobtypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wlworkflow_jobtypes', function (Blueprint $table) {
            $table->boolean('is_can_be_created')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wlworkflow_jobtypes', function (Blueprint $table) {
            $table->dropColumn('is_can_be_created');
        });
    }
}
