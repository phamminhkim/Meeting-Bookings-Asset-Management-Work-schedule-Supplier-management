<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveRuleChangePhaseFromWlworkflows extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wlworkflows', function (Blueprint $table) {
            $table->dropColumn('rule_change_phase');
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
            //
        });
    }
}
