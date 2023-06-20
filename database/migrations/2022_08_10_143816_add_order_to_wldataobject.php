<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderToWldataobject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wldataobjects', function (Blueprint $table) {
            $table->integer('order')->notnull()->default(0)->after('wldatatype_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wldataobjects', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
