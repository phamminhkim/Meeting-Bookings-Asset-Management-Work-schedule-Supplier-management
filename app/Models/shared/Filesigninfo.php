<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class Filesigninfo extends Model
{
    public $fillable = ['id', 'file_id','show_sign','sign','x','y','width','height','width_c','height_c','cx','cy','page','fieldname','created_at','updated_at'];
}
