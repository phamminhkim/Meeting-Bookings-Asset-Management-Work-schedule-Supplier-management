<?php

namespace App\Models\asset;

use Illuminate\Database\Eloquent\Model;

class AssetDetail extends Model
{
    protected $fillable = ['id', 'objectable_id', 'objectable_type', 'name', 'value','asset_type_id'];
    public function objectable()
    {
        return $this->morphTo();
    }
}
