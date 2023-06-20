<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitCurrencies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('currencies',function($table){
            $table->string('id',3)->primary()->change();//đơn vị
            $table->string('unit',30)->nullable();//đơn vị
            $table->string('and',10)->nullable();//và
            $table->string('odd',30)->nullable();//số lẻ
            $table->string('twounit',1)->default('');//số lẻ
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('currencies',function($table){
            $table->dropPrimary('id',3)->change();//đơn vị
            $table->dropColumn('unit');
            $table->dropColumn('and');
            $table->dropColumn('odd');
            $table->dropColumn('twounit');
            
        });
    }
}
