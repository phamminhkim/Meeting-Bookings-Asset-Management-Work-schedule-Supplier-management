<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWfstepdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wfstepdetails', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('wfstep_Id')->unsigned()  ;
            $table->integer('wfusertype_id')->nullable() ;
            $table->integer('wftapptype_id')->nullable() ;
            $table->integer('level') ;
            $table->boolean('required') ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wfstepdetails');
    }
}
