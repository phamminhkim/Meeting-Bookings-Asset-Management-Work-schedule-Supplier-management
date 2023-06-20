<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class CarError extends Model
{
    protected $fillable = ['id','name','description','active','created_at','updated_at'];
    public function department(){
        return $this->belongsTo(Department::class);
    }
}
