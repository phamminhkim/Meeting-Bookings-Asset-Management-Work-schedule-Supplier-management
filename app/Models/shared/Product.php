<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['id','name','code_sap','unit','user_id'];
}
