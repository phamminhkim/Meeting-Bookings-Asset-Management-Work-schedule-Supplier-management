<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddYearUniqueIds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unique_ids',function($table){
            $table->string('year',4)->nullable();
            $table->boolean('auto')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unique_ids',function($table){
            $table->dropColumn('year');
            $table->dropColumn('auto');
        });
    }
}
