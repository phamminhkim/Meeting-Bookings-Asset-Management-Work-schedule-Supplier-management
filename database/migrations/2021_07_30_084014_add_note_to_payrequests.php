<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoteToPayrequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payrequests', function (Blueprint $table) {
            $table->string('note',150)->nullable();
            $table->string('bank_name',50)->nullable();
            $table->string('bank_branch',50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payrequests', function (Blueprint $table) {
            $table->dropColumn('note');
            $table->dropColumn('bank_name');
            $table->dropColumn('bank_branch');
        });
    }
}
