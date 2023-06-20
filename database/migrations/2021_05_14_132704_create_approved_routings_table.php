<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovedRoutingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approved_routings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('document_type_id')->unsigned();
            $table->bigInteger('approved_limit_id')->unsigned();
            $table->bigInteger('group_id')->unsigned();
            $table->bigInteger('team_id')->unsigned();
            $table->string('description');
            $table->string('name',50);
            $table->boolean('active',1)->default(1);
            $table->timestamps();
            $table->softDeletes();
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approved_routings');
    }
}
