<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_inventarios', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('responsible')->nullable();
            $table->bigInteger('asset_warehouse_id')->nullable();
            $table->dateTime('deadline_confirm')->nullable();
            $table->bigInteger('create_by')->nullable();
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
        Schema::dropIfExists('asset_inventarios');
    }
}
