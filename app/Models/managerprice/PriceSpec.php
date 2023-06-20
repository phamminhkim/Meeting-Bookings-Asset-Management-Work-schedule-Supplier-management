<?php

namespace App\Models\managerprice;

use Illuminate\Database\Eloquent\Model;

class PriceSpec extends Model
{
    protected $fillable = ['id','price_product_id','name','create_at','update_at'];
    protected $hidden = [
        'create_at', 'update_at',
    ];
    public function others(){
        return $this->hasMany(PriceSpecitem::class);
    }
}
