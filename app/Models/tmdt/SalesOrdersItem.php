<?php

namespace App\Models\tmdt;

use Illuminate\Database\Eloquent\Model;

class SalesOrdersItem extends Model
{
    public $table = "tmdt_saleorders_item";
    public $fillable = ['id', 'tmdt_saleorders_id', 'mavt', 'tenvt', 'soluong', 'soluong_qd', 'mahangsan', 'dvt', 'mahangsan'];
}
