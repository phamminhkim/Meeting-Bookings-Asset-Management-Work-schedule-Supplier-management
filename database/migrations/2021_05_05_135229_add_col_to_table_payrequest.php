<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColToTablePayrequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payrequests', function (Blueprint $table) {
            $table->bigInteger('payrequest_type_id')->nullable()->change();
            $table->bigInteger('department_id')->unsigned();
            $table->bigInteger('team_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payrequests', function (Blueprint $table) {
            $table->bigInteger('payrequest_type_id')->unsigned()->change();
            $table->bigInteger('team_id')->unsigned()->change();
            $table->dropColumn('department_id');
        });
    }
}
