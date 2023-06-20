<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniqueIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unique_ids', function (Blueprint $table) {
            $table->string('prefix',8);
            $table->string('module',10);
            $table->string('serial',6)->default('000000');
           

           
        });
        Schema::table('unique_ids', function (Blueprint $table) {
            $table->primary(['prefix','module']);

           
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unique_ids');
    }
}
