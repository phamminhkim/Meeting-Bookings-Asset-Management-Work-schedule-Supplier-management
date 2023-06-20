<?php

namespace App\Models\shared;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Wfapproved extends Model
{
    protected $fillable = ['id', 'user_id', 'level','wfapprovedable_id','wfapprovedable_type','checkin','checkout','release','note','version','online','step','finished','sign','created_at','updated_at','wfapptype_id','wfusertype_id','is_reminder','expected_time'];
    public function wfapprovedable(){
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
