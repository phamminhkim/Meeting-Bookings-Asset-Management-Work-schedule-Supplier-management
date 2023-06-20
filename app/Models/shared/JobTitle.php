<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class JobTitle extends Model
{
    protected $fillable = [
        'code', 'eng_name', 'vie_name'
    ];

    protected $hidden = [
        'updated_at', 'created_at'
    ];
}
