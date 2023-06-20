<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class ConfigSys extends Model
{
    public $timestamps = false;
	protected $fillable = [
        'value', 'id'
    ];
	  protected $casts = [
        'id' => 'string'
    ];
}
