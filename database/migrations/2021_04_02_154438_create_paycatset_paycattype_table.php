<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaycatsetPaycattypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paycatset_paycattype', function (Blueprint $table) {


            $table->bigInteger('paycatset_id')->unsigned();
            $table->bigInteger('paycattype_id')->unsigned();



        });		
        Schema::table('paycatset_paycattype', function (Blueprint $table) {

            $table->foreign('paycatset_id')->references('id')->on('paycatsets')->onDelete('cascade');
            $table->foreign('paycattype_id')->references('id')->on('paycattypes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paycatset_paycattype');
    }
}
