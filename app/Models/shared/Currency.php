<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
     
    protected $casts = [
        'id' => 'string'
    ];
}
