<?php

namespace App\Models\asset;

use App\Models\shared\Department;
use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\reminder\HasReminder;
use App\Traits\HasAttachmentFile;
use App\Traits\HasShared;
use App\Traits\HasTimeline;

class AssetUse extends Model
{
    use HasReminder, HasTimeline, HasShared, HasAttachmentFile;

    protected $fillable = ['id', 'user_id', 'objectable_id', 'objectable_type', 'quantity', 'asset_warehouse_id', 'date_transaction', 'time_complete_inven', 'create_by', 'department_id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function objectable()
    {
        return $this->morphTo();
    }
    public function Department()
    {
        return $this->belongsTo(Department::class);
    }
    public function AssetWarehouse()
    {
        return $this->belongsTo(AssetWarehouse::class);
    }
}
