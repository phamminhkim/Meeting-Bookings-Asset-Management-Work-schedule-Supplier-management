<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCompanyIdToShareds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shareds', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('department_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shareds', function (Blueprint $table) {
            $table->string('company_id',50)->nullable();
            $table->bigInteger('department_id')->unsigned()->nullable();
        });
    }
}
