<?php
namespace App\Traits;

use App\Models\shared\Timeline;

trait HasTimeline{
    public function timelines(){
        return $this->morphMany(Timeline::class,'timelineable')->with('user');
    }
}


?>