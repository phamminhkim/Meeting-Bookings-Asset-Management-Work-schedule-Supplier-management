<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TaoBangPhieugiaoHang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmdt_delivery_notes', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->text('donghang_ids');
            $table->string('username', 50);
            $table->string('companycode', 4);
            $table->string('sloc', 4)->nullable();
            $table->integer('print')->nullable();
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
        Schema::dropIfExists('tmdt_delivery_notes');
    }
}
