<?php

namespace App\Models\asset;

use Illuminate\Database\Eloquent\Model;

class AssetInventarioDetail extends Model
{
    protected $fillable = ['id', 'asset_inventario_id', 'user_id', 'objectable_id', 'objectable_type', 'asset_status_id', 'user_confirm_status', 'stocker_confirm_status', 'user_confirm_quantity', 'stocker_confirm_quantity', 'comment','quantity_use'];
    public function objectable()
    {
        return $this->morphTo();
    }
    public function AssetInventario()
    {
        return $this->belongsTo(AssetInventario::class);
    }

}
