<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TaoBangMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus',function($table){
            $table->bigIncrements('id');
            $table->string('title');
            $table->integer('parent')->default(0);
            $table->integer('sort')->default(0);
            $table->string('icon')->nullable();
            $table->string('link');
            $table->timestamps();

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus'); //
    }
}
