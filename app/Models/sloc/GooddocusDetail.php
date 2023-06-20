<?php

namespace App\Models\sloc;

use Illuminate\Database\Eloquent\Model;

class GooddocusDetail extends Model
{
    protected $fillable = ['id','good_id','goodunit_id','quantity','unit_price','amount','end_date','gooddocu_id','objectable_id','objectable_type'];
    public function goodunit()
    {
        return $this->belongsTo(Goodunits::class);
    }
    public function objectable(){
        return $this->morphTo();
        }
}
