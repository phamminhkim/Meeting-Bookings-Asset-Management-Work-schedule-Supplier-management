<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class ReviewRating extends Model
{
    public function objectable(){
    return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
  
   
}
