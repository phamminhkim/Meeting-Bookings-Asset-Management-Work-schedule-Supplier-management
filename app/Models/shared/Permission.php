<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'name', 'description', 
    ];
    public $table = 'permissions';
}
