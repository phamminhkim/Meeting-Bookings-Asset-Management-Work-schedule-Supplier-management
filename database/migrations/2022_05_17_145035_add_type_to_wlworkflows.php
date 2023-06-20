<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToWlworkflows extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wlworkflows', function (Blueprint $table) {
            $table->integer("type");//0: Qui trình mẫu , 1: qui trình cụ thể
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wlworkflows', function (Blueprint $table) {
            $table->dropColumn("type");
        });
    }
}
