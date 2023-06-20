<?php
namespace App\Traits;

use App\Models\shared\Shared;

trait HasShared{
    public function shareds(){
        return $this->morphMany(Shared::class,'sharedable')->with('user');
    }
}


?>
