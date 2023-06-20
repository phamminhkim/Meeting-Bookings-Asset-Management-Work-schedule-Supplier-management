<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnWfstepconfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('wfstepconfigs', function (Blueprint $table) {
			 $table->renameColumn('wfapptype_id','wfusertype_id');
         });
		  Schema::table('wfsteps', function (Blueprint $table) {
			 $table->renameColumn('wfapptype_id','wfusertype_id');
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
