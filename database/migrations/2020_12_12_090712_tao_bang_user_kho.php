<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TaoBangUserKho extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_sloc', function (Blueprint $table) {

            $table->bigInteger('role_id')->unsigned();
            $table->String('sloc_id',4);
            $table->timestamps();
        });

        Schema::table('role_sloc', function (Blueprint $table) {

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('sloc_id')->references('id')->on('slocs')->onDelete('cascade');
        });
    }


    public function down()
    {
        Schema::table('role_sloc', function (Blueprint $table) {
            $table->dropForeign('role_sloc_role_id_foreign')->references('id')->on('roles')->onDelete('cascade');
            $table->dropForeign('role_sloc_sloc_id_foreign')->references('id')->on('slocs')->onDelete('cascade');
        });


        Schema::dropIfExists('role_sloc'); //

    }
}
