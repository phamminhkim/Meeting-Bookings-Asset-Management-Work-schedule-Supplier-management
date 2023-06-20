<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSendDateWlworkflowAppdocTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wlworkflow_appdocs', function (Blueprint $table) {

            $table->dateTime('send_date')->nullable();
          
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
            $table->dropColumn('send_date'); 
         
        });
    }
}
