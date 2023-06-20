<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColFinalizationTableContractTerms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_terms', function (Blueprint $table) {
          
            $table->boolean('finalization')->default(0);
           
          
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
          
            $table->dropColumn('finalization');
           
          
        });
    }
}
