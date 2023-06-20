<?php

namespace App\Models\shared;
use App\User;
use Illuminate\Database\Eloquent\Model;

class CommentVote extends Model
{
    protected $fillable = ['comment_id','user_id','vote','created_at','updated_at'];
 
   protected $table = "comment_user_vote";
   
   public function user()
   {
       return $this->belongsTo(User::class);
   }
   public function comment()
   {
       return $this->belongsTo(Comment::class);
   }
}
