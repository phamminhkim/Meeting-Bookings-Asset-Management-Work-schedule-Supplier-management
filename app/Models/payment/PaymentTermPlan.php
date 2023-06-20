<?php

namespace App\Models\payment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentTermPlan extends Model
{

    protected $fillable = ['id','contract_term_id','contract_id','terms_num','content','date_due','amount','date_paid','amount_paid','reference_num','status','note','create_at','update_at','term_content','sub_contract_id'];
    protected $hidden = [
        'create_at', 'update_at',
    ];
}
