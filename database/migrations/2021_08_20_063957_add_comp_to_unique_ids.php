<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompToUniqueIds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unique_ids', function (Blueprint $table) {
            $table->string('company_id',4)->default("");

          
        });
        Schema::table('unique_ids', function (Blueprint $table) {
            $table->dropPrimary(['document_type_code','module']);

          
        });
        Schema::table('unique_ids', function (Blueprint $table) {
            $table->primary(['document_type_code','module','company_id']);

          
        });
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unique_ids', function (Blueprint $table) {
            $table->dropPrimary(['document_type_code','module','company_id']);

          
        });
     
        Schema::table('unique_ids', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });

        Schema::table('unique_ids', function (Blueprint $table) {
            $table->primary(['document_type_code','module']);

          
        });

    }
}
