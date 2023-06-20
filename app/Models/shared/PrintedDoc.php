<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class PrintedDoc extends Model
{
    public $fillable = ['id', 'objectable_id','objectable_type','content','created_at','updated_at'];
    public function objectable()
    {
        return $this->morphTo();
    }

}
