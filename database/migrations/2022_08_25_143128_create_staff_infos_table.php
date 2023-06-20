<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_infos', function (Blueprint $table) {
            $table->id();
            $table->string('staff_id')->unique();
            $table->bigInteger('employee_type')->unsigned();
            $table->bigInteger('employment_type')->unsigned();
            $table->bigInteger('direct_type')->unsigned();
            $table->bigInteger('jobtitle_type')->unsigned();
            $table->date('date_in');
            $table->date('date_out');
            $table->boolean('is_working');
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
        Schema::dropIfExists('staff_infos');
    }
}
