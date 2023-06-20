<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColWfstapconfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wfstepconfigs', function (Blueprint $table) {
            $table->bigInteger('wfmainconfig_id')->unsigned();
            $table->dropColumn('wfstepconfigable_id');
            $table->dropColumn('wfstepconfigable_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wfstepconfigs', function (Blueprint $table) {
            $table->bigInteger('wfstepconfigable_id')->unsigned();
            $table->string('wfstepconfigable_type');
            $table->dropColumn('wfmainconfig_id');
        });
        //  $table->string('wfstepconfigable_type');
    }
}
