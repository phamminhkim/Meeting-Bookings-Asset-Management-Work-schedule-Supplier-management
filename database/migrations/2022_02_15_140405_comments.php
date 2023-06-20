<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Comments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('comments');

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->text('content');
            $table->integer('votes')->default(0);
            $table->integer('spam')->default(0);
            $table->integer('commentable_id')->unsigned();
            $table->string('commentable_type');
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
         Schema::dropIfExists('comments');
		 Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('commentable_id')->unsigned();
            $table->string('commentable_type');
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->text('content');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->timestamps();
        });
    }
}
