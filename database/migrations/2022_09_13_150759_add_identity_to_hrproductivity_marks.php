<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdentityToHrproductivityMarks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hrproductivity_marks', function (Blueprint $table) {
            $table->integer('company_id')->after('hrproductivity_staff_id');
            $table->integer('department_id')->after('company_id');
            $table->integer('workshop_id')->nullable()->after('department_id');
            $table->integer('party_id')->nullable()->after('workshop_id');

            $table->renameColumn('hrproductivity_staff_id', 'user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hrproductivity_marks', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('department_id');
            $table->dropColumn('workshop_id');
            $table->dropColumn('party_id');

            $table->renameColumn('user_id', 'hrproductivity_staff_id');
        });
    }
}
