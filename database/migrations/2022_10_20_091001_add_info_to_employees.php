<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInfoToEmployees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('name', 100)->default('')->after('staff_id');
            $table->string('company_id', 4)->nullable(true)->after('name');
            $table->integer('department_id')->unsigned()->nullable(true)->after('company_id');;
            $table->integer('workshop_id')->unsigned()->nullable(true)->after('department_id');;
            $table->integer('party_id')->unsigned()->nullable(true)->after('workshop_id');;
            $table->tinyInteger('gender')->unsigned()->nullable(true)->after('party_id');;
            $table->renameColumn('staff_id', 'employee_id');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('department_id');
            $table->dropColumn('workshop_id');
            $table->dropColumn('party_id');
            $table->dropColumn('gender');
            $table->renameColumn('employee_id', 'staff_id');;
        });
    }
}
