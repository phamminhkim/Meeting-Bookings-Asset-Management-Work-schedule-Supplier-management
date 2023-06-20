<?php

namespace App\Models\asset;

use Illuminate\Database\Eloquent\Model;

class AssetMy extends Model
{
    protected $fillable = ['id','user_id','objectable_id','objectable_type','quantity','confirm','amount','asset_transaction_id'];
}
