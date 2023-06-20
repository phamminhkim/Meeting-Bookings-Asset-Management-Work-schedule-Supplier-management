<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;
use App\Models\shared\ApprovedLimit;
use App\Models\shared\Team;
class ApprovedRouting extends Model
{
    protected $fillable = [
        'document_type_id', 'id','approved_limit_code','group_id','team_id','description','active','name','payment_type_id' ];
    
    public function approved_limit(){
        return $this->belongsTo(ApprovedLimit::class);
    }
    public function team(){
        return $this->belongsTo(Team::class)->withCount(['users','userccs']);
    }
    public function payment_type(){
        return $this->belongsTo(PaymentType::class);
    }
    public function document_type(){
        return $this->belongsTo(DocumentType::class);
    }
    public function group(){
        return $this->belongsTo(Group::class);
    }
}
