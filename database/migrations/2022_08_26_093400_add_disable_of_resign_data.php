<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDisableOfResignData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('resign_data', function (Blueprint $table) {
            $table->boolean('is_available', 50)->default(true)->after('official_resigntime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('resign_data', function (Blueprint $table) {
            $table->dropColumn('is_available');
        });
    }
}
