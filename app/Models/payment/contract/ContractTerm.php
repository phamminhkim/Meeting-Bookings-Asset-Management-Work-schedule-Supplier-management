<?php

namespace App\Models\payment\contract;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractTerm extends Model
{

    protected $fillable = [
        'id', 'contract_id', 'finalization', 'sub_contract_id', 'term_content', 'isused', 'frequency', 'frequency_type', 'duration', 'terms_num', 'content', 'date_due', 'amount', 'date_paid', 'amount_paid', 'reference_num', 'status', 'create_at', 'update_at', 'note'
    ];
    protected $hidden = [
        'create_at', 'update_at',
    ];
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
