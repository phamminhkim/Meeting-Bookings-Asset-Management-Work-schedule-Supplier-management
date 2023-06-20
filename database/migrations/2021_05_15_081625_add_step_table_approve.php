<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStepTableApprove extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('approveds',function (Blueprint $table){
            $table->integer('step')->default(1);
            $table->string('review',1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('approveds',function (Blueprint $table){
            $table->dropColumn('step');
            $table->dropColumn('review');
        });
    }
}
