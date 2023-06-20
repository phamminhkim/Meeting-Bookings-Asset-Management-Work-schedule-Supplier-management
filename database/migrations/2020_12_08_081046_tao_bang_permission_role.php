<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TaoBangPermissionRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_permission', function (Blueprint $table) {


            $table->bigInteger('role_id')->unsigned();
            $table->bigInteger('permission_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('role_permission', function (Blueprint $table) {

            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
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

        // Schema::table('role_permission', function (Blueprint $table) {

        //     $table->dropForeign('role_permission_permission_id_foreign')->references('id')->on('permissions')->onDelete('cascade');
        //     $table->dropForeign('role_permission_role_id_foreign')->references('id')->on('roles')->onDelete('cascade');

        // });

        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('role_permission');
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
