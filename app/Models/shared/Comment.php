<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\shared\OtherAttached;
class Comment extends Model
{
    /**
     * Get the owning commentable model.
     */
	protected $fillable = ['id ','user_id','parent_id','content','commentable_id','commentable_type','created_at','updated_at'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
    public function commentable()
    {
        return $this->morphMany(Comment::class,'commentable');
    }
    public function other_attacheds(){
        return $this->morphMany(OtherAttached::class,'objectable')->with('user','attachment_file');
    }
    public function comment_votes()
    {
        return $this->hasMany(CommentVote::class,'comment_id')->with('user');
    }
    
}
