<?php

namespace App\Models\managerprice;

use Illuminate\Database\Eloquent\Model;

class PriceSpecitem extends Model
{
    protected $fillable = ['id','price_product_id','price_spec_id','vendor_id','vendor_display','value','create_at','update_at'];
    protected $hidden = [
        'create_at', 'update_at',
    ];
}
