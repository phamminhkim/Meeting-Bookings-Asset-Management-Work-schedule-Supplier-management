<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class Wfmainconfig extends Model
{
    protected $fillable = ['id', 'wfmainconfigable_id','wfmainconfigable_type','name','description','created_at','updated_at'];
    public function wfmainconfigable()
    {
        return $this->morphTo();
    }
}
