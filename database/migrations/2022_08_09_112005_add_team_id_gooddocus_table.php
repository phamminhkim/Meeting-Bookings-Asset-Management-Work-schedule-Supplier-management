<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeamIdGooddocusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('gooddocus', function (Blueprint $table) {
        //     $table->string('company_id',4);
        //     $table->bigInteger('department_id');
        //     $table->bigInteger('document_type_id');
        //     $table->bigInteger('group_id');
        //     $table->bigInteger('team_id')->nullable();;
        //     $table->integer('finished')->nullable();;
        //     $table->integer('status')->nullable();;
        //     $table->dateTime('send_date')->nullable();
        //     $table->bigInteger('teamconfig_id')->nullable();;
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('gooddocus', function (Blueprint $table) {
        //     $table->dropColumn('company_id');
        //     $table->dropColumn('department_id');
        //     $table->dropColumn('document_type_id');
        //     $table->dropColumn('group_id');
        //     $table->dropColumn('team_id');
        //     $table->dropColumn('finished');
        //     $table->dropColumn('status');
        //     $table->dropColumn('send_date');
        //     $table->dropColumn('teamconfig_id');
        // });
    }
}
