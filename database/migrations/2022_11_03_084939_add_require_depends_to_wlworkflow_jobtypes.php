<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRequireDependsToWlworkflowJobtypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wlworkflow_jobtypes', function (Blueprint $table) {
            $table->boolean('is_require_depends')->default(false);
            $table->string('require_depends_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wlworkflow_jobtypes', function (Blueprint $table) {
            $table->dropColumn('is_require_depends');
            $table->dropColumn('require_depends_text');
        });
    }
}
