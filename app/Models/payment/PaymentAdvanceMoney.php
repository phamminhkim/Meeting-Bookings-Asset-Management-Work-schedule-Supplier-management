<?php

namespace App\Models\payment;

use Illuminate\Database\Eloquent\Model;

class PaymentAdvanceMoney extends Model
{
    protected $fillable = ['id','payrequest_id','advance_money_id','finished','serial_num','serial_date','amount','create_at','update_at'];
    protected $hidden = [
        'create_at', 'update_at',
    ];
    public function refer(){
        return $this->belongsTo(Payrequest::class,'advance_money_id','id');
    }
}
