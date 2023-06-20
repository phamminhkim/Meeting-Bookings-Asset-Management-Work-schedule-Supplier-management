<?php

namespace App\Models\managerprice;

use Illuminate\Database\Eloquent\Model;

class PriceDetail extends Model
{
    protected $fillable = ['id','price_product_id','vendor_id','quantity',
    'price_old','price', 'choose','brand','is_vat','vendor_display','create_at','update_at'];
    protected $hidden = [
        'create_at', 'update_at',
    ];
}
