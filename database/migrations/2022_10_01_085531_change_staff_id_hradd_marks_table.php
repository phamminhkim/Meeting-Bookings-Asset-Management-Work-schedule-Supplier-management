<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeStaffIdHraddMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hradd_marks', function (Blueprint $table) {
            $table->renameColumn('staff_id','staff_code')->change();
            
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
            $table->renameColumn('staff_code','staff_id')->change();
            
        });
    }
}
