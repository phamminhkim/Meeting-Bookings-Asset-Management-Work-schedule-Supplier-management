<?php

namespace App\Models\work\workflow;

use App\Traits\HasAttachmentFile;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasScope;
use App\Traits\HasConfig;

class Wldataobject extends Model
{
    use HasAttachmentFile, HasScope, HasConfig;
    
    protected $fillable = [
        'id', 'unique_name', 'name', 'wlworkflow_id', 'reference_path', 'wlphase_id', 'wldatatype_id', 'order', 'require', 'read_only', 'description', 'index_after_by', 'created_at', 'updated_at', 'value', 'value_num', 'value_date', 'value_time', 'value_boolean', 'refer_id', 'wljob_id'
    ];

    protected $casts = [
        'require' => 'boolean',
        'read_only' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(WldataobjectItms::class, "wldataobject_id", "id")->orderBy('order');
    }
    public function childs()
    {
        return $this->hasMany(Wldataobject::class, "index_after_by", "id");
    }
    public function parent()
    {
        return $this->belongsTo(Wldataobject::class, "index_after_by", "id");
    }
}
