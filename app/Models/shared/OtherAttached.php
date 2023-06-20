<?php

namespace App\Models\shared;

use App\User;
use Illuminate\Database\Eloquent\Model;

class OtherAttached extends Model
{
    protected $fillable = ['id','objectable_id','objectable_type','user_id','name','note','checked','create_at','update_at'];
    protected $hidden = [
        'create_at', 'update_at',
    ];
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
