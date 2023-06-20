<?php

namespace App\Models\payment;

use Illuminate\Database\Eloquent\Model;
use App\Models\shared\File;
//Chứng từ thanh toán
class PaymentVoucher extends Model
{
    protected $fillable = ['id','payrequest_id','type','doc_num','doc_date','amount','content','costcenter','gl_account','prpo_num','prpo_type','create_at','update_at'];
    protected $hidden = [
        'create_at', 'update_at',
    ];
    public function attachment_file(){
        return $this->morphMany(File::class,'fileable');
    }
    public function typeObj(){
        return $this->hasOne(PaymentVoucherType::class,'id','type');
    }
}
