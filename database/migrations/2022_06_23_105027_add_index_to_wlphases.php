<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToWlphases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wlphases', function (Blueprint $table) {
            $table->integer('index')->nullable();
            $table->dateTime('finished_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wlphases', function (Blueprint $table) {
            $table->dropColumn('index');
            $table->dropColumn('finished_date');
        });
    }
}
