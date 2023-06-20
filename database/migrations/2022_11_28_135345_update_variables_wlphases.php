<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateVariablesWlphases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wlphases', function (Blueprint $table) {
            $table->renameColumn('allowaddjob', 'allow_add_job');
            $table->boolean('allow_send_response')->default(false)->after('allowaddjob');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wlphases', function (Blueprint $table) {
            $table->renameColumn('allow_admin_withdraw_job', 'allowaddjob');
            $table->dropColumn('allow_send_response');
        });
    }
}
