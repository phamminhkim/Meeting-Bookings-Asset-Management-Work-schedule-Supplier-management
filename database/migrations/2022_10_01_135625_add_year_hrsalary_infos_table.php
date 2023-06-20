<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddYearHrsalaryInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hrsalary_infos', function (Blueprint $table) {
            $table->integer('year');
            $table->integer('month');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hrsalary_infos', function (Blueprint $table) {
            $table->dropColumn('year');
            $table->dropColumn('month');
            
        });
    }
}
