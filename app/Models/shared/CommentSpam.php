<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class CommentSpam extends Model
{
   protected $fillable = ['comment_id','user_id','created_at','updated_at'];
 
   protected $table = "comment_spam";
}
