<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CommentUserVote extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_user_vote', function (Blueprint $table) {
 
           $table->integer('comment_id');
 
           $table->integer('user_id');
 
           $table->string('vote',11);
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
         Schema::dropIfExists('comment_user_vote');
    }
}
