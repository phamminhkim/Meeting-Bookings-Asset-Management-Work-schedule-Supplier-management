<?php

namespace App\Models\asset;
use App\User;
use Illuminate\Database\Eloquent\Model;

class AssetInventario extends Model
{
    protected $fillable = ['id','name','responsible','asset_warehouse_id','deadline_confirm','time_confirm','create_by','description','complete'];
    
    public function AssetInventarioDetails(){
        return $this->hasMany(AssetInventarioDetail::class);
    }
    public function AssetWarehouse()
    {
        return $this->belongsTo(AssetWarehouse::class);
    }
    public function AssetInventarioHistorys(){
        return $this->hasMany(AssetInventarioHistory::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'create_by');
    }
}
