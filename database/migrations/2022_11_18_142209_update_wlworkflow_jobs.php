<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateWlworkflowJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wlworkflow_jobs', function (Blueprint $table) {
            $table->renameColumn('allow_assign_user', 'allow_admin_assign_user');
            $table->boolean('allow_user_assign_another_user')->default(false)->after('allow_assign_user');
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
            $table->renameColumn('allow_admin_assign_user', 'allow_assign_user');
            $table->dropColumn('allow_user_assign_another_user');
        });
    }
}
