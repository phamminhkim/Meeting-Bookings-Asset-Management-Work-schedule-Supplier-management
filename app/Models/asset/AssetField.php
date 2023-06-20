<?php

namespace App\Models\asset;

use Illuminate\Database\Eloquent\Model;

class AssetField extends Model
{
    protected $fillable = ['id','name','description','value','create_by'];
}
