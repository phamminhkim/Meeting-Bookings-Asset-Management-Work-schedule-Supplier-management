<?php

namespace App\Models\payment\contract;

use Illuminate\Database\Eloquent\Model;
use App\Models\payment\contract\Contract;
use App\Models\shared\File;

class ContractLiquidation extends Model
{
    protected $fillable = [
        'id', 'contract_id', 'create_at', 'update_at', 'content', 'date_liquid', 'user_id'
    ];
    protected $hidden = [
        'create_at', 'update_at',
    ];
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
    public function attachment_file()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
