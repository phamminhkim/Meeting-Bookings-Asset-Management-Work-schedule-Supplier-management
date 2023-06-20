<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovedIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('approveds', function (Blueprint $table) {
            $table->index(['approvedable_id', 'approvedable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('approveds', function (Blueprint $table) {
            $table->dropIndex(['approvedable_id', 'approvedable_type']);
        });
    }
}
