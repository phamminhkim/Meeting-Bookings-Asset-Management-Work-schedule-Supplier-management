<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColApproveLimitCodeFromApprovedRoutings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('approved_routings', function (Blueprint $table) {
           
            try {
                $table->dropForeign(['approved_limit_id']);
            } catch (\Throwable $th) {
                //throw $th;
            }
          
            $table->dropColumn('approved_limit_id');
            $table->string('approved_limit_code',30);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('approved_routings', function (Blueprint $table) {
            $table->bigInteger('approved_limit_id')->unsigned();
            $table->dropColumn('approved_limit_code');
        });
       
    }
}
