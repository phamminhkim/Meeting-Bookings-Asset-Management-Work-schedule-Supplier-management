<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;
use App\Models\shared\ApprovedLimit;
class DocumentType extends Model
{
    protected $fillable = [
        'id', 'code','name','created_at','module','updated_at','active',
        'currency_approved','private','approve_manual','document_group_id'
    ];
    public function approved_limits(){
        return $this->hasMany(ApprovedLimit::class);
    }
    public function document_group(){
        return $this->belongsTo(DocumentGroup::class);
    }

}
