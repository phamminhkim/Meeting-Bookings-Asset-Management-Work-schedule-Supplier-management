<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAllowAssignUserWlworkflowJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wlworkflow_jobs', function (Blueprint $table) {
            $table->boolean('allow_assign_user')->default(true)->after('allow_self_assign_job');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wlworkflow_jobs', function (Blueprint $table) {
            $table->dropColumn('allow_assign_user');
        });
    }
}
