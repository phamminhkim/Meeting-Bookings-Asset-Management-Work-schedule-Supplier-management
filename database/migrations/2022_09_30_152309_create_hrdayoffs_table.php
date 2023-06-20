<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrdayoffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrdayoffs', function (Blueprint $table) {
            $table->id();
            $table->string('company_id',4);
            $table->bigInteger('staff_code');
            $table->integer('year');
            $table->integer('month');
            $table->integer('day');
            $table->string('reason')->nullable();
            $table->bigInteger('updated_by')->unsigned();
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
        Schema::dropIfExists('hrdayoffs');
    }
}
