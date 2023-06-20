<?php

namespace App\Models\work\workflow;

use Illuminate\Database\Eloquent\Model;

class WlworkflowScope extends Model
{
    protected $fillable = [
        'id', 'name', 'json_value', 'created_at', 'updated_at'
    ];

    public function objectable()
    {
        return $this->morphTo();
    }
}
