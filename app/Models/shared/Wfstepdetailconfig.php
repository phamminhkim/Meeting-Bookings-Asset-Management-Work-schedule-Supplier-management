<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class Wfstepdetailconfig extends Model
{
    protected $fillable = ['id','wfstepconfig_id','wfusertype_id','level','wftapptype_id','required'];
    public function wfstepconfig(){
        return $this->belongsTo(wfstepconfig::class);
    }
    public function wfusertype(){
        return $this->belongsTo(Wfusertype::class);
    }
    public function wftapptype(){
        return $this->belongsTo(wftapptype::class);
    }
}
