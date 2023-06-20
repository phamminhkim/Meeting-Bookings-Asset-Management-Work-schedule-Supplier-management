<?php

namespace App\Models\asset;

use Illuminate\Database\Eloquent\Model;

class AssetGroup extends Model
{
    protected $fillable = ['id','name','create_by','description'];
}
