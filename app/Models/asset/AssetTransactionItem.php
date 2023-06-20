<?php

namespace App\Models\asset;

use App\Models\shared\Department;
use App\User;
use  App\Models\asset\AssetDetail;
use Illuminate\Database\Eloquent\Model;

class AssetTransactionItem extends Model
{
    protected $fillable = ['id','asset_transaction_id','asset_warehouse_id','objectable_id','objectable_type','quantity','asset_status_id','user_id','quantity_sloc','department_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function AssetWarehouse()
    {
        return $this->belongsTo(AssetWarehouse::class);
    }
    public function objectable(){
        return $this->morphTo();
    }
    public function AssetTransaction()
    {
        return $this->belongsTo(AssetTransaction::class);
    }
    public function AssetStatus()
    {
        return $this->belongsTo(AssetStatus::class);
    }
    public function Asset()
    {
        return $this->belongsTo(Asset::class);
    }
    public function AssetTool()
    {
        return $this->belongsTo(AssetTool::class);
    }
    public function Department(){
        return $this->belongsTo(Department::class);
    }
    public function History(){
   
        return $this->belongsTo(AssetFixedHistory::class,'id','asset_transaction_item_id');
    }
}
