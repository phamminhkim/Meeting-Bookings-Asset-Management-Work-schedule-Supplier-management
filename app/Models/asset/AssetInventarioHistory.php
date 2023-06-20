<?php

namespace App\Models\asset;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\reminder\HasReminder;
use App\Traits\HasAttachmentFile;
use App\Traits\HasShared;
use App\Traits\HasTimeline;
class AssetInventarioHistory extends Model
{
    use HasReminder,HasTimeline, HasShared,HasAttachmentFile;
    public $table = 'asset_inventario_history';
    protected $fillable = ['id', 'asset_inventario_id', 'user_id', 'objectable_id', 'objectable_type', 'asset_status_id', 'user_confirm_status', 'stocker_confirm_status', 'user_confirm_quantity', 'stocker_confirm_quantity', 'comment','quantity_use','discrepancy'];
    public function objectable()
    {
        return $this->morphTo();
    }
    public function AssetInventario()
    {
        return $this->belongsTo(AssetInventario::class);
    }

}
