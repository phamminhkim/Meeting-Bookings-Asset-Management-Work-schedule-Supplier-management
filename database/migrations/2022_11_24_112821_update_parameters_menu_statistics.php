<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateParametersMenuStatistics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('menu_statistics', function (Blueprint $table) {
            $table->string('menu_parameter')->after('menu_class');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_statistics', function (Blueprint $table) {
            $table->dropColumn('menu_parameter');
        });
    }
}
