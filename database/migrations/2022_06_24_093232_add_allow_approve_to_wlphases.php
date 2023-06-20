<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAllowApproveToWlphases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wlphases', function (Blueprint $table) {
            $table->integer('approve_type')->nullable();//null: không hiển thị duyệt; 0:chọn cây duyệt manual; 1: Cây duyệt tự động
            $table->bigInteger('team_id')->unsigned()->nullable();
            $table->bigInteger('teamconfig_id')->unsigned()->nullable();
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
            $table->dropColumn('approve_type');//null: không hiển thị duyệt; 0:chọn cây duyệt manual; 1: Cây duyệt tự động
            $table->dropColumn('team_id');
            $table->dropColumn('teamconfig_id');
        });
    }
}
