<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetFixedHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_fixed_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('asset_id');
            $table->string('name')->nullable();
            $table->integer('quantity')->nullable();
            $table->bigInteger('objectable_id')->nullable();
            $table->string('objectable_type')->nullable();
            $table->bigInteger('asset_status_id')->nullable();
            $table->string('old_content')->nullable();
            $table->bigInteger('old_component')->nullable();
            $table->string('new_content')->nullable();
            $table->bigInteger('new_component')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('asset_fixed_histories');
    }
}
