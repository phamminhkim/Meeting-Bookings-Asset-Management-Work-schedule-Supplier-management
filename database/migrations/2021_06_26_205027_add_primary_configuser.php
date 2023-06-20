<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrimaryConfiguser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('config_users', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->string('code',20);
            $table->primary(['user_id', 'code']);
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('config_users', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->string('id',20);
            $table->primary(['user_id', 'code']);
        });
    }
}
