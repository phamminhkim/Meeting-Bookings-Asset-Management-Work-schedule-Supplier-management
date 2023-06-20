<?php

namespace App\Models\work\workflow;

use Illuminate\Database\Eloquent\Model;

class WlworkflowAdmin extends Model
{
    protected $fillable = [
        'id', 'objectable_id', 'objectable_type','user_id','created_at','updated_at' ];

    public function objectable(){
        return $this->morphTo();
    }
}
