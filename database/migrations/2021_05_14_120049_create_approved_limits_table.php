<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovedLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approved_limits', function (Blueprint $table) {
            $table->id();
            $table->string('comapany_id',4);
            $table->bigInteger('document_type_id')->unsigned();
            $table->float('from');
            $table->float('to');
            $table->string('currency',3);
            $table->integer('budget_type');
            $table->string('name',50);
            $table->boolean('active',1)->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approved_limits');
    }
}
