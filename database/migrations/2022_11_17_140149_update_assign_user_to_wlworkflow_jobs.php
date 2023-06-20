<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAssignUserToWlworkflowJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wlworkflow_jobs', function (Blueprint $table) {
            $table->bigInteger('assign_user')->unsigned()->nullable()->after('action_user');
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
            $table->dropColumn('assign_user');
        });
    }
}
