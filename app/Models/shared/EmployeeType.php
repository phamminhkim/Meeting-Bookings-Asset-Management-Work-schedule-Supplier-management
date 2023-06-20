<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class EmployeeType extends Model
{
    protected $fillable = [
        'code', 'name'
    ];

    protected $hidden = [
        'updated_at', 'created_at'
    ];
}
