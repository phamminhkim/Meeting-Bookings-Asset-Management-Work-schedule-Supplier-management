<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColNameTableFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files', function (Blueprint $table) {
           
            $table->string('name',100)->nullable();
            $table->string('ext',10)->nullable();
            $table->integer('size')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('files', function (Blueprint $table) {
           
            $table->dropColumn('name');
            $table->dropColumn('ext');
            $table->dropColumn('size');
            
        });
    }
}
