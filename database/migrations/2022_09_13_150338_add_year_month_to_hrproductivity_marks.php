<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddYearMonthToHrproductivityMarks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hrproductivity_marks', function (Blueprint $table) {
            $table->integer('year')->after('hrproductivity_staff_id');
            $table->integer('month')->after('year');
            $table->integer('day')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hrproductivity_marks', function (Blueprint $table) {
            $table->dropColumn('year');
            $table->dropColumn('month');
            $table->integer('day')->nullable(false)->change();
        });
    }
}
