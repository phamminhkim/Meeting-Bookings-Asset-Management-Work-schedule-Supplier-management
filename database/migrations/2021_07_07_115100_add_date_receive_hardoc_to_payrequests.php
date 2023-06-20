<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateReceiveHardocToPayrequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payrequests', function (Blueprint $table) {
            $table->date('date_receive_hard_doc')->nullable();
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
            $table->dropColumn('date_receive_hard_doc');
        });
    }
}
