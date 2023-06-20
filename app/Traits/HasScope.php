<?php
namespace App\Traits;

use App\Models\work\workflow\WlworkflowScope;

trait HasScope{
    public function scope()
    {
        return $this->morphOne(WlworkflowScope::class,'objectable');
    }
}
