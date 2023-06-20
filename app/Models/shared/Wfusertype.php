<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class Wfusertype extends Model
{
    protected $fillable = ['id', 'name','description','active','created_at','updated_at'];
}
