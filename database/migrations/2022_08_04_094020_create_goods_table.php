<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->id();
            
            $table->string('code',50);
            $table->string('name',100);
            $table->string('description')->nullable();
            $table->integer('goodunit_id')->unsigned();
            $table->string('size',100)->nullable();

            $table->string('color',100)->nullable();
            $table->string('weight',100)->nullable();
            $table->float('open_stock')->nullable();



         
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
        Schema::dropIfExists('goods');
    }
}
