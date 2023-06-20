<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('comp_name',120);
            $table->string('signer',50)->nullable();
            $table->string('position',50)->nullable();
            $table->string('tax_code',50);
            $table->string('phone',50)->nullable();
            $table->string('email',50)->nullable();
            $table->bigInteger('company_id')->unsigned()->nullable();
            $table->string('address',120)->nullable();
            $table->string('contact',50)->nullable();
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
        Schema::dropIfExists('vendors');
    }
}
