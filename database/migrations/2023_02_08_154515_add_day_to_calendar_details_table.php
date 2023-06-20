<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDayToCalendarDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calendar_details', function (Blueprint $table) {
            $table->bigInteger('day')->nullable();
            $table->bigInteger('year')->nullable();
            $table->longText('work')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calendar_details', function (Blueprint $table) {
            $table->dropColumn('day');
            $table->dropColumn('year');


        });
    }
}
