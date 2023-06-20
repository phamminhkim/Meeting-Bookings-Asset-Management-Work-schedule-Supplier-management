<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameStaffIdToHrproductivityStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hrproductivity_staff', function (Blueprint $table) {
            $table->renameColumn('staff_id', 'staff_code');
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
            $table->renameColumn('staff_code', 'staff_id');
        });
    }
}
