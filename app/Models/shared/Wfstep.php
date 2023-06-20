<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class Wfstep extends Model
{
    protected $fillable = ['id', 'wfmain_id','next_user','name','description','step','form_approval','is_end','created_at','updated_at','wfusertype_id','code'];
    public function wfmain(){
        return $this->belongsTo(Wfmain::class);
    }
    public function details(){
        return $this->hasMany(Wfstepdetail::class);
    }
}
