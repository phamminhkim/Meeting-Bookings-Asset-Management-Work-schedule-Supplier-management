<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TaoBangDanhmucKho extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slocs', function ($table) {
            $table->String('id', 4)->primary();
            $table->String('name', 50);
            $table->string('description',50);
            $table->String('active', 1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { Schema::dropIfExists('slocs'); //
    }
}
