<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class DirectType extends Model
{
    protected $fillable = [
        'code', 'name'
    ];

    protected $hidden = [
        'updated_at', 'created_at'
    ];
}
