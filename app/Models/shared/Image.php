<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
     /**
     * Get the owning commentable model.
     */
    public function imgable(){
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function signs()
    {
        return $this->hasMany(Filesigninfo::class);
    }

}
