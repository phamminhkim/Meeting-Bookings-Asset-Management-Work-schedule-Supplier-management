<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignkeyTablePriceSpecsAndPriceSpecitem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('price_specitems',function($table){
        $table->foreign('price_spec_id')->references('id')->on('price_specs')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_specitems',function($table){
            $table->dropForeign('price_specitems_price_spec_id_foreign');
           });
    }
}
