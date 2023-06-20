<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWlworkflowConfigs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wlworkflow_configs', function (Blueprint $table) {
            $table->id();
            $table->string('keyword');
            $table->morphs('objectable');
            $table->json('options')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wlworkflow_configs', function (Blueprint $table) {
            Schema::dropIfExists('wlworkflow_configs');
        });
    }
}
