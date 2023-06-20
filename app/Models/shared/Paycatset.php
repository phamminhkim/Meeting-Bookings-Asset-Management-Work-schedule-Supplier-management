<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class Paycatset extends Model
{
    public function paycattypes()
    {
        return $this->belongsToMany(Paycattype::class,'paycatset_paycattype');
    }
     
}
