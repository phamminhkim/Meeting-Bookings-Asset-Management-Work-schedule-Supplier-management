<?php

namespace App\Models\asset;
use App\Repositories\reminder\HasReminder;
use App\Traits\HasAttachmentFile;
use App\Traits\HasShared;
use App\Traits\HasTimeline;
use Illuminate\Database\Eloquent\Model;
use App\User;

class AssetType extends Model
{
    use HasReminder,HasTimeline, HasShared,HasAttachmentFile;
    protected $fillable = ['id','name','code','type','asset_group_id','amount','description','active','asset_cat_id','create_by'];

    public function AssetGroup()
    {
        return $this->belongsTo(AssetGroup::class);
    }
    public function AssetCat()
    {
        return $this->belongsTo(AssetCat::class);
    }
    public function AssetTypeDetails()
    {
        return $this->hasMany(AssetTypeDetail::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'create_by');
    }
    public function AssetWarehouse()
    {
        return $this->hasMany(AssetWarehouse::class);
    }
    
}
