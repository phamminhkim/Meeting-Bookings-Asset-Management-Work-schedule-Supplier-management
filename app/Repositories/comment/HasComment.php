<?php
namespace App\Repositories\comment;

use App\Models\shared\Comment;

trait HasComment {


        //Chỉ lấy 1 những nhắc nhở của user login
        public function comment_one(){
            return $this->morphOne(Comment::class,'commentable')->where('user_id',auth()->user()->id);
        }

        public function comment(){
            return $this->morphMany(Comment::class,'commentable');
        }



}
?>
