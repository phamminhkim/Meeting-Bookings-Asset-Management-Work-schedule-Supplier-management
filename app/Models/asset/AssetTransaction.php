<?php

namespace App\Models\asset;

use App\Models\shared\Department;
use App\User;
use Illuminate\Database\Eloquent\Model;

class AssetTransaction extends Model
{
    protected $fillable = ['id', 'confirm', 'user_id', 'trans_type', 'create_by', 'asset_policy_id', 'note', 'asset_warehouse_id', 'department_id', 'date_transaction', 'assetdable_id', 'assetdable_type', 'created_at'];
    public function assetdable()
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function createBy()
    {
        return $this->belongsTo(User::class, 'create_by');
    }
    public function AssetPolicy()
    {
        return $this->belongsTo(AssetPolicy::class);
    }
    public function AssetTransactionItems()
    {
        return $this->hasMany(AssetTransactionItem::class);
    }
    public function Asset()
    {
        return $this->belongsTo(Asset::class);
    }
    public function AssetTool()
    {
        return $this->belongsTo(AssetTool::class);
    }
    public function AssetWarehouse()
    {
        return $this->belongsTo(AssetWarehouse::class);
    }
    public function Department()
    {
        return $this->belongsTo(Department::class);
    }
    
    
}
