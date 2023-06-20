<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddColDocumentTypeCodeTouniqueIds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        
        Schema::table('unique_ids', function (Blueprint $table) {
           
            $table->renameColumn('prefix','document_type_code');
            $table->string('letter',8)->nullable();
            
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
           
            $table->renameColumn('document_type_code','prefix');
            $table->dropColumn('letter');
           
        });
    }
}
