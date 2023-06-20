<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableEmployees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->bigInteger('employee_type')->unsigned()->nullable(true)->change();
            $table->bigInteger('employment_type')->unsigned()->nullable(true)->change();
            $table->bigInteger('direct_type')->unsigned()->nullable(true)->change();
            $table->bigInteger('jobtitle_type')->unsigned()->nullable(true)->change();
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
            $table->bigInteger('employee_type')->unsigned()->nullable(false)->change();
            $table->bigInteger('employment_type')->unsigned()->nullable(false)->change();
            $table->bigInteger('direct_type')->unsigned()->nullable(false)->change();
            $table->bigInteger('jobtitle_type')->unsigned()->nullable(false)->change();
        });
    }
}
