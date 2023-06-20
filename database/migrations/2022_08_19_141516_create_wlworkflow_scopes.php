<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWlworkflowScopes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wlworkflow_scopes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->morphs('objectable');
            $table->json('json_value')->nullable();
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
        Schema::table('wlworkflow_scopes', function (Blueprint $table) {
            Schema::dropIfExists('wlworkflow_scopes');
        });
    }
}
