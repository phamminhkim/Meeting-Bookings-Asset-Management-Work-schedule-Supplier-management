<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameUserIdToHrproductivityMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hrproductivity_marks', function (Blueprint $table) {
            $table->renameColumn('user_id', 'staff_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hrproductivity_marks', function (Blueprint $table) {
            $table->renameColumn('staff_code', 'user_id');
        });
    }
}
