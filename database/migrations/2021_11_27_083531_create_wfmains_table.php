<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWfmainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wfmains', function (Blueprint $table) {
            $table->id();
            $table->string('company_id',4)  ;
            $table->bigInteger('document_type_id')->unsigned();
            $table->string('name',100)  ;
            $table->string('description')  ;
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
        Schema::dropIfExists('wfmains');
    }
}
