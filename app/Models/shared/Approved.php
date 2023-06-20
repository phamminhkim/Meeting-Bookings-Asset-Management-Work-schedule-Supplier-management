<?php

namespace App\Models\shared;

use App\Models\payment\Payrequest;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Approved extends Model
{
    public function approvedable(){
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
