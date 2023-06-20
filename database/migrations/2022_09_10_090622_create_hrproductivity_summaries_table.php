<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrproductivitySummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrproductivity_summaries', function (Blueprint $table) {
            $table->id();
            $table->string('company_id',4);
            $table->bigInteger('department_id')->unsigned();
            $table->bigInteger('workshop_id')->unsigned();
            $table->bigInteger('party_id')->unsigned();
            $table->integer('year');
            $table->integer('month');
            $table->bigInteger('staff_id')->unsigned();
            $table->float('mark_final')->default(0.0);
            $table->string('type_rank',20);
            $table->float('money_ref',18)->default(0.0);
            $table->float('money_final',18)->default(0.0);
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
        Schema::dropIfExists('hrproductivity_summaries');
    }
}
