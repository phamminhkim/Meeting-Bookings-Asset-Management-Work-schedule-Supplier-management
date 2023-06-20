<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValueBooleanToWldataobjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wldataobjects', function (Blueprint $table) {
            $table->boolean('value_boolean')->nullable();
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
            $table->dropColumn('value_boolean');
        });
    }
}
