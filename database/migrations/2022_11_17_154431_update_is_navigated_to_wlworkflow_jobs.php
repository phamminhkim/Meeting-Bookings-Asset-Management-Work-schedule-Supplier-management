<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIsNavigatedToWlworkflowJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wlworkflow_jobs', function (Blueprint $table) {
            $table->boolean('is_navigated')->nullable()->after('is_denied');
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
            $table->dropColumn('is_navigated');
        });
    }
}
