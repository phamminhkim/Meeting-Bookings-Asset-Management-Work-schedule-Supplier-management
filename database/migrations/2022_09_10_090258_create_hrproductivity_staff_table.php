<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrproductivityStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrproductivity_staff', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('hrproductivity_doc_id')->unsigned();
            $table->bigInteger('staff_id')->unsigned();
            $table->float('totalday_calc')->default(0.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrproductivity_staff');
    }
}
