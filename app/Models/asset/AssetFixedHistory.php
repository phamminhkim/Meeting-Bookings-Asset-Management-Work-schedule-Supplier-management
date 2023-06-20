<?php

namespace App\Models\asset;

use Illuminate\Database\Eloquent\Model;

class AssetFixedHistory extends Model
{
    protected $fillable = ['id','asset_id','name','quantity','objectable_id','objectable_type','asset_status_id','old_content','old_component','new_content','new_component','description','asset_transaction_item_id'];

    public function objectable(){
        return $this->morphTo();
    }
    public function Asset()
    {
        return $this->belongsTo(Asset::class);
    }
    public function NewComponent()
    {
        return $this->belongsTo(AssetTool::class,'new_component','id');
    }
    public function OldComponent()
    {
        return $this->belongsTo(AssetTool::class,'old_component','id');
    }
    
}
