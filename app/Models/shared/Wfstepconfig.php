<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class Wfstepconfig extends Model
{
    protected $fillable = ['id','wfmainconfig_id','name','description','step','next_user','form_approval','is_end','wfusertype_id'];
    public function wfmainconfig(){
        return $this->belongsTo(Wfmainconfig::class);
    }
}
