<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColSubContractIdTableContractTerms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_terms', function (Blueprint $table) {
          
            $table->bigInteger('sub_contract_id')->unsigned()->nullable();
           
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_terms', function (Blueprint $table) {
          
            $table->dropColumn('sub_contract_id');
           
          
        });
    }
}
