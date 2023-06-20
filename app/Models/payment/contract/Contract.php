<?php

namespace App\Models\payment\contract;


use App\Models\payment\contract\ContractTerm;
use App\Models\shared\Company;
use App\Models\shared\File;
use App\Models\shared\Vendor;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\payment\contract\ContractLiquidation;
use App\Models\payment\PaymentTermPlan;

class Contract extends Model
{

    protected $fillable = ['contract_num','status','money_type','reason_end','finalization','contract_type','parent_id','sign_date','a_signer','a_position','b_signer','b_position','vendor_id','date_begin','date_end','title','content','amount','amount_paid','payment_status','bugget_id','company_id','department_id','create_at','update_at','econtract_id','user_id','delete_at'];
    protected $hidden = [
        'create_at', 'update_at',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function contract_terms()
    {
        return $this->hasMany(ContractTerm::class);
    }
    public function contract_term_plans()
    {
        return $this->hasMany(PaymentTermPlan::class);
    }
    public function attachment_file(){
        return $this->morphMany(File::class,'fileable');
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function contract_liquidation(){
        return $this->hasOne(ContractLiquidation::class)->with('attachment_file');
    }
    public function vendor(){
        return $this->belongsTo(Vendor::class);
    }

    public function childs()
    {
        return $this->hasMany(Contract::class, 'parent_id', 'id')->with('contract_terms');
    }
    public function parent()
    {
        return $this->hasOne(Contract::class, 'id', 'parent_id')->with('contract_terms');;
    }
}
