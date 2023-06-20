<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameStaffCodeToHrproductivityStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hrproductivity_staff', function (Blueprint $table) {
            $table->renameColumn('staff_code', 'update_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hrproductivity_staff', function (Blueprint $table) {
            $table->renameColumn('update_by', 'staff_code');
        });
    }
}
