<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TaoBangSaleorders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmdt_saleorders', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('masan', 4);
            $table->string('madonhangsan', 100);
            $table->string('mavanchuyen', 100)->nullable()->unique();
            $table->string('madonhang', 10)->nullable();
            $table->biginteger('nguoisoan')->nullable();
            $table->biginteger('nguoidonggoi')->nullable();
            $table->biginteger('nguoigiao')->nullable();
            $table->string('nguoinhan', 50)->nullable();

            $table->string('ghichu', 150)->nullable();
            $table->integer('trangthai')->default(0);
            $table->string('username', 50);
            $table->timestamps();
            $table->unique(['masan', 'madonhangsan']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tmdt_saleorders');
    }
}
