<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColBudgetTypePayrequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payrequests',function (Blueprint $table){
            $table->bigInteger('document_type_id')->unsigned();
            $table->integer('bugget_type')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payrequests',function (Blueprint $table){
            $table->dropColumn('document_type_id');
            $table->dropColumn('bugget_type');
        });
    }
}
