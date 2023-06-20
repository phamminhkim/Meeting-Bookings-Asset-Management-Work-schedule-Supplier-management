<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuStatistics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('menus', function (Blueprint $table) {
            $table->index(['link']);
        });

        Schema::create('menu_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('menu_link');
            $table->string('menu_class');
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
        Schema::dropIfExists('menu_statistics');

        Schema::table('menus', function (Blueprint $table) {
            $table->dropIndex(['link']);
        });
    }
}
