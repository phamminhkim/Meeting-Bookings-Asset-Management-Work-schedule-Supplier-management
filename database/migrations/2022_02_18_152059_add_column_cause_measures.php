<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCauseMeasures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cause_measures', function (Blueprint $table) {
            $table->string('person_in_charge');
        });
        Schema::table('fast_processes', function (Blueprint $table) {
            $table->string('person_in_charge');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cause_measures', function (Blueprint $table) {
            $table->dropColumn('person_in_charge');
        });
        Schema::table('fast_processes', function (Blueprint $table) {
            $table->dropColumn('person_in_charge');
        });
    }
}
