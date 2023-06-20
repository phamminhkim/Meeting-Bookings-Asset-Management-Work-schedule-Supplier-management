<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToWljobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('wljobs', 'description')) 
        {
            Schema::table('wljobs', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
     
        Schema::table('wljobs', function (Blueprint $table) {
            $table->string('description')->nullable()->default('')->after('name');
            $table->string('note')->nullable()->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wljobs', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('note');
        });
    }
}
