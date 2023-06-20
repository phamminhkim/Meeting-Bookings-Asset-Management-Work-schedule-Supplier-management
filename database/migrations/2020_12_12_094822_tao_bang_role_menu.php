<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TaoBangRoleMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_menu', function (Blueprint $table) {


            $table->bigInteger('role_id')->unsigned();
            $table->bigInteger('menu_id')->unsigned();



        });

        // Schema::table('role_sloc', function (Blueprint $table) {

        //     $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        //     $table->foreign('sloc_id')->references('id')->on('slocs')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_menu'); //
    }
}
