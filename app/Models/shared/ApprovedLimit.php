<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;
use App\Models\shared\Company;
use App\Models\shared\DocumentType;
class ApprovedLimit extends Model
{
    protected $fillable = [
          'id','document_type_id','from','to','from_sub','to_sub','currency','budget_type','description','company_id','name','active','code','payment_type_id'
    
    ];

    public function document_type(){
        return $this->belongsTo(DocumentType::class);
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }

    public function approved_routings(){
        return $this->hasMany(ApprovedRouting::class);
    }

}
