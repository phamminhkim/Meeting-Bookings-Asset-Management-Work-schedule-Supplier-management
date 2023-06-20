<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TaoBangSalesordersItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmdt_saleorders_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tmdt_saleorders_id')->unsigned();
            $table->string('mavt', 10);
            $table->string('tenvt', 255);
            $table->float('soluong');
            $table->float('soluong_qd')->nullable();
            $table->string('dvt', 15);
            $table->string('mahangsan', 50)->nullable();
            $table->timestamps();
            //Tạo khóa ngoại
            $table->foreign('tmdt_saleorders_id')->references('id')->on('tmdt_saleorders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('tmdt_saleorders_item', function (Blueprint $table) {
        //     $table->dropForeign('tmdt_saleorders_id')->references('id')->on('tmdt_saleorders')->onDelete('cascade');
        // });
        Schema::dropIfExists('tmdt_saleorders_item');
    }
}
