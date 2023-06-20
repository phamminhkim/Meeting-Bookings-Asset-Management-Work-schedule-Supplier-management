<?php

namespace App\Models\car;

use Illuminate\Database\Eloquent\Model;
use App\Models\shared\OtherAttached;

class FastProcess extends Model
{
    protected $fillable = [
        'id', 'car_id', 'content', 'date', 'create_at', 'update_at','person_in_charge'
    ];
    protected $hidden = [
        'create_at', 'update_at',
    ];
    public function car(){
        return $this->belongsTo(Car::class);
    }
    public function other_attacheds(){
        return $this->morphMany(OtherAttached::class,'objectable')->with('user','attachment_file');
    }
}
