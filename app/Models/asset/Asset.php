<?php

namespace App\Models\asset;
use App\User;

use App\Repositories\reminder\HasReminder;
use App\Traits\HasAttachmentFile;
use App\Traits\HasShared;
use App\Traits\HasTimeline;
use Illuminate\Database\Eloquent\Model;
use App\Models\shared\Vendor;
use App\Models\asset\AssetStatus;
use App\Models\asset\AssetGroup;
use App\Models\asset\AssetWarehouse;
use App\Models\asset\AssetTypeDetail;
use App\Models\shared\Department;
use App\Models\shared\File;

class Asset extends Model
{
     use HasReminder,HasTimeline, HasShared,HasAttachmentFile;
    
    protected $fillable = ['id','asset_type_id','asset_warehouse_id','name','asset_group_id','waiting','vendor_id',
    'seri','hashtag','amount','producer','add_date','asset_status_id','end_date','description','user_id','quantity',
    'waiting','create_by','sap_code','department_id','asset_code'];
    public $table = 'assets';
    // public function assset()
    // {
    //     return $this->belongsTo(Asset::class);
    // }
    public function AssetTypeDetails()
    {
        return $this->belongsTo(AssetTypeDetail::class);
    }
    public function AssetType()
    {
        return $this->belongsTo(AssetType::class);
    }
    public function status(){
        return $this->belongsTo(AssetStatus::class);
    }
    public function group(){
        return $this->belongsTo(AssetGroup::class);
    }
    public function warehouses(){
        return $this->belongsTo(AssetWarehouse::class);
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    public function Department(){
        return $this->belongsTo(Department::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function AssetWarehouse()
    {
        return $this->belongsTo(AssetWarehouse::class);
    }
    public function AssetGroup()
    {
        return $this->belongsTo(AssetGroup::class);
    }
    public function AssetDetails()
    {
        return $this->hasMany(AssetDetail::class,'objectable_id','id')->where('objectable_type', Asset::class);
    }
    public function objectable(){
        return $this->morphTo();
    }
    public function AssetFixedHistory()
    {
        return $this->hasMany(AssetFixedHistory::class,'asset_id','id' );
    }
    public function AssetTransaction()
    {
        return $this->hasMany(AssetTransaction::class,'assetdable_id','id' )->where('assetdable_type', Asset::class);
    }
    public function AssetTransactionItem()
    {
        return $this->hasMany(AssetTransactionItem::class,'objectable_id','id' )->where('objectable_type', Asset::class);
    }
}
