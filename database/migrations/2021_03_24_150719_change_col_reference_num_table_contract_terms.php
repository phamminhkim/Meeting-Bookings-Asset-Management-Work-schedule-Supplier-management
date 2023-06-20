<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColReferenceNumTableContractTerms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_terms', function (Blueprint $table) {
            $table->string('reference_num')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('contract_terms', function (Blueprint $table) {
        //     $table->string('reference_num')->nullable(false)->change();
        // });
    }
}
