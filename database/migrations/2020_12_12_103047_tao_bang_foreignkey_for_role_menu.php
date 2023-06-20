<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TaoBangForeignkeyForRoleMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('role_menu', function (Blueprint $table) {

            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('role_menu', function (Blueprint $table) {
            $table->dropForeign('role_menu_menu_id_foreign')->references('id')->on('menus')->onDelete('cascade');
            $table->dropForeign('role_menu_role_id_foreign')->references('id')->on('roles')->onDelete('cascade');
        });
    }
}
