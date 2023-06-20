<?php

namespace App\Models\asset;

use App\Repositories\reminder\HasReminder;
use App\Traits\HasAttachmentFile;
use App\Traits\HasShared;
use App\Traits\HasTimeline;
use Illuminate\Database\Eloquent\Model;


class AssetTypeDetail extends Model
{
    use HasReminder,HasTimeline, HasShared,HasAttachmentFile;

    protected $table='asset_types_details';
    protected $fillable = ['id','asset_type_id','name','value','objectable_id'];
    public function objectable(){
        return $this->morphTo();
        }
        public function Assets()
    {
        return $this->belongsTo(Asset::class);
    }
      
       
   
    
}
