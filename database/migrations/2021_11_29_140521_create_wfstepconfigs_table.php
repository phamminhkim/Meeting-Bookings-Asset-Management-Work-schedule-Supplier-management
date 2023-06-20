<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWfstepconfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wfstepconfigs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('wfstepconfigable_id')->unsigned();
            $table->string('wfstepconfigable_type');
            $table->string('name',100);
            $table->string('description',100)->nullable();
            $table->bigInteger('next_user')->unsigned();
            $table->string('form_approval')->nullable();
            $table->boolean('is_end')->nullable() ;
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
        Schema::dropIfExists('wfstepconfigs');
    }
}
