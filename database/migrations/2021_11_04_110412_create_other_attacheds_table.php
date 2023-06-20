<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherAttachedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_attacheds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('objectable_id')->unsigned();
            $table->string('objectable_type');
            $table->string('name',50);
            $table->string('note',50)->nullable();
            $table->integer('checked')->nullable();
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
        Schema::dropIfExists('other_attacheds');
    }
}
