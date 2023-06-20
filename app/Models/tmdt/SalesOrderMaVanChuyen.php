<?php

namespace App\Models\tmdt;

use Illuminate\Database\Eloquent\Model;

class SalesOrderMaVanChuyen extends Model
{
    public $table = "tmdt_saleorders_mavanchuyen";
    public $fillable = ['madonhang', 'mavanchuyen', 'userid'];
}
