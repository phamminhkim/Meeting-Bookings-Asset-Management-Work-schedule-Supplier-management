<?php

namespace App\Models\shared;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    public $fillable = ['id','timelineable_id','timelineable_type','icon','title','content','user_id'];
    public function timelineable(){
        return $this->morphTo();

    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
