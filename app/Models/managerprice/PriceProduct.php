<?php

namespace App\Models\managerprice;

use Illuminate\Database\Eloquent\Model;

class PriceProduct extends Model
{
    protected $fillable = ['id','price_req_id','code_sap','name','unit','create_at','update_at'];
    protected $hidden = [
        'create_at', 'update_at',
    ];
    public function details(){
        return $this->hasMany(PriceDetail::class);
    }
    public function specs(){
        return $this->hasMany(PriceSpec::class)->with('others');
    }
}
