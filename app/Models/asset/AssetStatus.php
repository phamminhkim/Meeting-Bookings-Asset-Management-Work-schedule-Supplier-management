<?php

namespace App\Models\asset;

use Illuminate\Database\Eloquent\Model;

class AssetStatus extends Model
{
    protected $fillable= ['id','name'];
    public $incrementing =false;
}
