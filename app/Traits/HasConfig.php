<?php

namespace App\Traits;

use App\Models\work\workflow\WlworkflowConfig;

trait HasConfig
{
    public function configs()
    {
        return $this->morphMany(WlworkflowConfig::class, 'objectable');
    }
    public function activeConfig()
    {
        return $this->morphOne(WlworkflowConfig::class, 'objectable')->where('active', 1)->limit(1);
    }
}
