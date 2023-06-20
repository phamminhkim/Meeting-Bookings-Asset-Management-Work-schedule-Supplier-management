<?php

namespace App\Models\car;

use Illuminate\Database\Eloquent\Model;

class Standard extends Model
{
    protected $fillable = [
        'id', 'name', 'description', 'active', 'create_at', 'update_at'
    ];
    protected $hidden = [
        'create_at', 'update_at',
    ];
    public function car(){
        return $this->belongsTo(Car::class);
    }
}
