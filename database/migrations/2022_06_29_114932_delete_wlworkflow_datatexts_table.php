<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteWlworkflowDatatextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('wlworkflow_datatexts');
        Schema::dropIfExists('wlworkflow_params');
        Schema::dropIfExists('wlworkflow_samples');
        Schema::dropIfExists('wlworkflow_statuses');

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
