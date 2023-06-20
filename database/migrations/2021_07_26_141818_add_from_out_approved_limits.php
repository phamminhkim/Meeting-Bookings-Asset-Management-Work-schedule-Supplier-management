<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFromOutApprovedLimits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('approved_limits', function (Blueprint $table) {
            $table->float('from_sub',18,2)->default(0);
            $table->float('to_sub',18,2)->default(0);
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
            $table->dropColumn('from_sub');
            $table->dropColumn('to_sub');
        });
    }
}
