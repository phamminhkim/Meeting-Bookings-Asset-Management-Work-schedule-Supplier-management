<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyOfStaffInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_infos', function (Blueprint $table) {
            $table->foreign('staff_id')->references('username')->on('users');
            $table->foreign('employee_type')->references('id')->on('employee_types');
            $table->foreign('employment_type')->references('id')->on('employment_types');
            $table->foreign('direct_type')->references('id')->on('direct_types');
            $table->foreign('jobtitle_type')->references('id')->on('job_titles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_infos', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
            $table->dropForeign(['employee_type']);
            $table->dropForeign(['employment_type']);
            $table->dropForeign(['direct_type']);
            $table->dropForeign(['jobtitle_type']);
        });
    }
}
