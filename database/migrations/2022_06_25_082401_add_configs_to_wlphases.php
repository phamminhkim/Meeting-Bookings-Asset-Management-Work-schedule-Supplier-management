<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConfigsToWlphases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wlphases', function (Blueprint $table) {
            $table->boolean('addOwnerToPhaseAdmins')->default(false);
            $table->boolean('allowPhaseMemberAssignJob')->default(false);
            $table->boolean('limitJobUserByPhaseMember')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wlphases', function (Blueprint $table) {
            $table->dropColumn('addOwnerToPhaseAdmins');
            $table->dropColumn('allowPhaseMemberAssignJob');
            $table->dropColumn('limitJobUserByPhaseMember');
        });
    }
}
