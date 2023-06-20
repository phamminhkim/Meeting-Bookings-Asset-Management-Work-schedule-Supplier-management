<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TaoForeignkeyForRolePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('role_permission', function (Blueprint $table) {

        //     $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
        //     $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('role_permission', function (Blueprint $table) {
        //     $table->dropForeign('role_permission_permission_id_foreign')->references('id')->on('permissions')->onDelete('cascade');
        //     $table->dropForeign('role_permission_role_id_foreign')->references('id')->on('roles')->onDelete('cascade');
        // });
    }
}
