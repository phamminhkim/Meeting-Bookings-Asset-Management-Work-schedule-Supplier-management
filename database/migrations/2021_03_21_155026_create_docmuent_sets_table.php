<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocmuentSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('docmuent_sets', function (Blueprint $table) {
        //     $table->id();
        //     $table->bigInteger('docmuent_type_id')->unsigned();
        //     $table->string('name',100);
        //     $table->integer('sort');
        //     $table->integer('active');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('docmuent_sets');
    }
}
