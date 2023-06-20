<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSendDateWlworkflowDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wlworkflow_docs', function (Blueprint $table) {

            $table->dateTime('send_date')->nullable();
            $table->bigInteger('teamconfig_id')->unsigned()->nullable();
            $table->integer('locked')->nullable();
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wlworkflow_docs', function (Blueprint $table) {
            $table->dropColumn('send_date'); 
            $table->dropColumn('teamconfig_id'); 
            $table->dropColumn('locked'); 
         
        });
    }
}
