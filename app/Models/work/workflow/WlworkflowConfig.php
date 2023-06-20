<?php

namespace App\Models\work\workflow;

use Illuminate\Database\Eloquent\Model;

class WlworkflowConfig extends Model
{
    protected $fillable = [
        'id', 'keyword', 'objectable_id', 'objectable_type', 'options', 'active', 'created_at', 'updated_at'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function objectable()
    {
        return $this->morphTo();
    }
}
