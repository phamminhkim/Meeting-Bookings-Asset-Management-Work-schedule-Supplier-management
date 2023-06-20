<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColTableContractTerm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_terms', function (Blueprint $table) {
          
            $table->integer('isused')->nullable();
            $table->integer('frequency')->nullable();
            $table->integer('frequency_type')->nullable();
            $table->integer('duration')->nullable();
          
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
          
            $table->dropColumn('isused');
            $table->dropColumn('frequency');
            $table->dropColumn('frequency_type');
            $table->dropColumn('duration');
          
        });
    }
}
