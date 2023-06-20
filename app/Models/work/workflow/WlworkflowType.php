<?php

namespace App\Models\work\workflow;

use Illuminate\Database\Eloquent\Model;

class WlworkflowType extends Model
{
    protected $fillable = [
        'id', 'name', 'description', 'active'
    ];

    protected $casts = [
        'id' => 'string',
    ];
}
