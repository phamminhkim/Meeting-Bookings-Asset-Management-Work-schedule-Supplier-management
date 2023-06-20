<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResignDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resign_data', function (Blueprint $table) {
            $table->id();
            $table->string('staff_id');
            $table->bigInteger('resign_type')->unsigned();
            $table->date('estimate_resigntime')->nullable();
            $table->boolean('is_resigned')->default(false);
            $table->date('official_resigntime')->nullable();
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
        Schema::dropIfExists('resign_data');
    }
}
