<?php

namespace App\Models\tmdt;

use Illuminate\Database\Eloquent\Model;

class SaleOrders extends Model
{
    public $table = "tmdt_saleorders";


    public $fillable = ['id', 'masan', 'madonhangsan', 'madonhang', 'mavanchuyen',  'ghichu', 'username', 'trangthai', 'nguoisoan', 'nguoidonggoi', 'nguoigiao', 'nguoigiao', 'nguoinhan', 'created_at', 'updated_at', 'companycode', 'sloc'];

    public function items()
    {
        return $this->hasMany('App\Models\tmdt\SalesOrdersItem', 'tmdt_saleorders_id', 'id');
    }
}
