<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesigninfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filesigninfos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('file_id')->unsigned();
            $table->string('show_sign',1)->default('X');
            $table->integer('sign')->default(1);
            $table->float('x',18,4)->default(0);
            $table->float('y',18,4)->default(0);
            $table->float('cx',18,4)->default(0);
            $table->float('cy',18,4)->default(0);
            $table->float('width',18,4)->default(0);
            $table->float('height',18,4)->default(0);
            $table->float('width_c',18,4)->default(0);
            $table->float('height_c',18,4)->default(0);
            $table->integer('page')->default(1);
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
        Schema::dropIfExists('filesigninfos');
    }
}
