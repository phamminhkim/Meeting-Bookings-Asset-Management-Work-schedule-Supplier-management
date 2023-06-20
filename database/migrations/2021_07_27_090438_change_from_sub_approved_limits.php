<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFromSubApprovedLimits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('approved_limits', function (Blueprint $table) {
            $table->float('from_sub')->nullable()->change();
            $table->float('to_sub')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('approved_limits', function (Blueprint $table) {
            $table->float('from_sub',18,2)->default(0)->change();
            $table->float('to_sub',18,2)->default(0)->change();
        });
    }
}
