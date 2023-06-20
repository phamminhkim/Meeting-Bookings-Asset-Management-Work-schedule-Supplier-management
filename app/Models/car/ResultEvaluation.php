<?php

namespace App\Models\car;

use App\Models\shared\OtherAttached;
use Illuminate\Database\Eloquent\Model;

class ResultEvaluation extends Model
{
    protected $fillable = [
        'id', 'car_id', 'content', 'result', 'date', 'create_at', 'update_at'
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
