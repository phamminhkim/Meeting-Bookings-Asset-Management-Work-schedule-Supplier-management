<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateWithdrawWlworkflowJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wlworkflow_jobs', function (Blueprint $table) {
            $table->renameColumn('allow_withdraw_job', 'allow_admin_withdraw_job');
            $table->boolean('allow_user_withdraw_job')->default(false)->after('allow_withdraw_job');
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
            $table->renameColumn('allow_admin_withdraw_job', 'allow_withdraw_job');
            $table->dropColumn('allow_user_withdraw_job');
        });
    }
}
