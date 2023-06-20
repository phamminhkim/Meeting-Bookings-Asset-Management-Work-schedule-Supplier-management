<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableDataobjecttype extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('wldatatypes', 'wldataobject_type');

        Schema::table('wldataobject_type', function (Blueprint $table) {
            $table->tinyInteger('type')->nullable(false)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wldataobject_type', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::rename('wldataobject_type', 'wldatatypes');

    }
}
