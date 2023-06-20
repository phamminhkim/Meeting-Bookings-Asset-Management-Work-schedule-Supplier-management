<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeignKeyApprovedRoutingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('approved_routings', function (Blueprint $table) {

            $table->foreign('document_type_id')->references('id')->on('document_types')->onDelete('RESTRICT')->onUpdate('RESTRICT');
            $table->foreign('approved_limit_id')->references('id')->on('approved_limits')->onDelete('RESTRICT')->onUpdate('RESTRICT');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('RESTRICT')->onUpdate('RESTRICT');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('RESTRICT')->onUpdate('RESTRICT');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('approved_routings', function (Blueprint $table) {
            $table->dropForeign(['document_type_id']);
            $table->dropForeign(['approved_limit_id']);
            $table->dropForeign(['team_id']);
            $table->dropForeign(['group_id']);
        });
    }
}
