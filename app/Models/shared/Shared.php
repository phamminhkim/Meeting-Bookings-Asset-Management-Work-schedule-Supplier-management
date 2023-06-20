<?php

namespace App\Models\shared;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Shared extends Model
{
    protected $fillable = ['id','sharedable_id','sharedable_type','object_id','create_at','update_at','user_id','type'];
    protected $hidden = [
        'create_at', 'update_at',
    ];
    public function sharedable(){
        return $this->morphTo();
    }
    public function group(){
        if ($this->type == 0) {
            return $this->belongsTo(Group::class,'object_id')->with('company');
        }else {
            return null;
        }

    }
    public function viewer(){
        if ($this->type == 1) {
            return $this->belongsTo(User::class,'object_id');
        }else {
            return null;
        }

    }
    public function department(){
        if ($this->type == 2) {
            return $this->belongsTo(Department::class,'object_id')->with('company');
        }else {
            return null;
        }

    }
    public function company(){
        if ($this->type == 3) {
            return $this->belongsTo(Company::class,'object_id') ;
        }else {
            return null;
        }

    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function assign(){
        if ($this->type == 4) {
            return $this->belongsTo(User::class,'object_id');
        }else {
            return null;
        }

    }
}
