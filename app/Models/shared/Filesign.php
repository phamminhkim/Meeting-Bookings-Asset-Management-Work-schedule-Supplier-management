<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class Filesign extends Model
{

    public $fillable = ['id', 'objectable_id','objectable_type','user_id','signed','status','created_at','updated_at'];
    public function attachment_file(){
        return $this->morphMany(File::class,'fileable');
    }
    public function objectable()
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
