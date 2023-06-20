<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColContractTermIdTablePayrequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         
        Schema::table('payrequests',function(Blueprint $table){
            $table->bigInteger('contract_term_id')->unsigned()->nullable();
            $table->bigInteger('contract_id')->unsigned()->nullable()->change();
         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payrequests',function(Blueprint $table){
            $table->dropColumn('contract_term_id');
           
        });
    }
}
