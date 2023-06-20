<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAllowanceToWljobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wljobs', function (Blueprint $table) {
            $table->tinyInteger('allow_withdraw_job')->nullable(false)->default(0);
            $table->tinyInteger('allow_abandon_job')->nullable(false)->default(0);
            $table->tinyInteger('allow_self_assign_job')->nullable(false)->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wljobs', function (Blueprint $table) {
            $table->dropColumn('allow_withdraw_job');
            $table->dropColumn('allow_abandon_job');
            $table->dropColumn('allow_self_assign_job');
        });
    }
}
