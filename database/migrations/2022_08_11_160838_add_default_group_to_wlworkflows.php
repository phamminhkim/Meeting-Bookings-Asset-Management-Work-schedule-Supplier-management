<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultGroupToWlworkflows extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wlworkflows', function (Blueprint $table) {
            $table->integer('default_group')->nullable();//null: không hiển thị duyệt; 0:chọn cây duyệt manual; 1: Cây duyệt tự động
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
            $table->dropColumn('default_group');
        });
    }
}
