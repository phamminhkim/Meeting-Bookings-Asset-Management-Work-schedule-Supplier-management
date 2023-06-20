<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColCompanyIdToHraddMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hradd_marks', function (Blueprint $table) {
            $table->string('company_id',4)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hradd_marks', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
    }
}
