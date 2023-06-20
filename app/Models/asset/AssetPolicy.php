<?php

namespace App\Models\asset;

use App\Repositories\reminder\HasReminder;
use App\Traits\HasAttachmentFile;
use App\Traits\HasShared;
use App\Traits\HasTimeline;
use App\Models\shared\File;
use Illuminate\Database\Eloquent\Model;

class AssetPolicy extends Model
{
    use HasReminder,HasTimeline, HasShared,HasAttachmentFile;
    protected $fillable = ['id','name','create_by','type','policy_conditions','active'];
    
    
}
