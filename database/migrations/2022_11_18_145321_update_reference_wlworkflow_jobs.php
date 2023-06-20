<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateReferenceWlworkflowJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wlworkflow_jobs', function (Blueprint $table) {
            $table->renameColumn('get_by_reference', 'is_action_user_by_ref');
            $table->renameColumn('reference_path', 'action_user_path_ref');

            $table->boolean('is_assign_user_by_ref')->default(false);
            $table->string('assign_user_path_ref')->nullable();
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
            $table->renameColumn('is_action_user_by_ref', 'get_by_reference');
            $table->renameColumn('action_user_path_ref', 'reference_path');

            $table->dropColumn('is_assign_user_by_ref');
            $table->dropColumn('assign_user_path_ref');
        });
    }
}
