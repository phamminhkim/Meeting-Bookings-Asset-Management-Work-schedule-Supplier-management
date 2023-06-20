<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyOfResignData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('resign_data', function (Blueprint $table) {
            $table->foreign('staff_id')->references('staff_id')->on('staff_infos');
            $table->foreign('resign_type')->references('id')->on('resign_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_infos', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
            $table->dropForeign(['resign_type']);
        });
    }
}
