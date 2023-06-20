<?php

namespace App\Models\asset;

use App\Repositories\reminder\HasReminder;
use App\Traits\HasAttachmentFile;
use App\Traits\HasShared;
use App\Traits\HasTimeline;
use App\Models\asset\AssetWarehouse;
use Illuminate\Database\Eloquent\Model;

use App\Models\shared\Department;
use App\Models\shared\Vendor;
use App\User;

class AssetTool extends Model
{
    use HasReminder,HasTimeline, HasShared,HasAttachmentFile;
    protected $fillable = ['id','asset_type_id','asset_warehouse_id','name','asset_group_id','vendor_id','hashtag','producer',
   
    'amount','quantity','sloc_tool','add_date','image_url','asset_status_id','description','create_by','sap_code','department_id','asset_code'];
    
    public function AssetType()
    {
        return $this->belongsTo(AssetType::class);
    }
    public function department(){
        return $this->belongsTo(Department::class);
    }
    public function AssetWarehouse()
    {
        return $this->belongsTo(AssetWarehouse::class);
    }
    public function AssetGroup()
    {
        return $this->belongsTo(AssetGroup::class);
    }
    public function sloc(){
        return $this->belongsTo(Sloc::class);
    }
     
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function transactions(){
        return $this->morphMany(AssetTransactionItem::class,'objectable');
    }
    public function AssetDetails()
    {
        return $this->hasMany(AssetDetail::class,'objectable_id','id')->where('objectable_type', AssetTool::class);
    }
}
