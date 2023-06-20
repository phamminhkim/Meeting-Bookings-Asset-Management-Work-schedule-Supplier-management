<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class ResignType extends Model
{
    protected $fillable = [
        'code', 'name', 'description', 'is_has_performance'
    ];

    protected $hidden = [
        'updated_at', 'created_at'
    ];
}
