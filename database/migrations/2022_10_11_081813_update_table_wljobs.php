<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableWljobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wljobs', function (Blueprint $table) {
            $table->renameColumn('job_type', 'job_type_id');
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
            $table->renameColumn('job_type_id', 'job_type');
        });
    }
}
