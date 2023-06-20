<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class Wfstepdetail extends Model
{
    protected $fillable = ['id', 'wfstep_id','wfusertype_id','wfapptype_id','level','required','active','created_at','updated_at'];
    public function wfstep(){
        return $this->belongsTo(Wfstep::class);
    }
    public function wfusertype(){
        return $this->belongsTo(Wfusertype::class);
    }
    public function wftapptype(){
        return $this->belongsTo(wftapptype::class);
    }
}
