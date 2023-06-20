<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniqueIdAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unique_id_assets', function (Blueprint $table) {
            $table->id();

            $table->string('module',10);
            $table->string('serial',6)->default('000000');
            $table->boolean('auto')->default(0);
            $table->string('company_id',4)->default("");
            $table->string('letter',8)->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unique_id_assets');
    }
}
