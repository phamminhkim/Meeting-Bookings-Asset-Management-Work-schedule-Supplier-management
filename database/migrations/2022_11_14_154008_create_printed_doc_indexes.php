<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintedDocIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('printed_docs', function (Blueprint $table) {
            $table->index(['objectable_id', 'objectable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('printed_docs', function (Blueprint $table) {
            $table->dropIndex(['objectable_id', 'objectable_type']);
        });
    }
}
