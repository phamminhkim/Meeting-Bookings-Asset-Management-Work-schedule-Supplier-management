<?php

namespace App\Models\payment;

use App\Models\payment\contract\Contract;
use App\Models\payment\contract\ContractTerm;
use App\Models\shared\Company;
use App\Models\payment\PaymentAttached;
use App\Models\shared\Approved;
use App\Models\shared\Bank;
use App\Models\shared\BudgetType;
use App\Models\shared\Department;
use App\Models\shared\DocumentType;
use App\Models\shared\Group;
use App\Models\shared\PaymentType;
use App\Models\shared\PrintedDoc;
use App\Models\shared\Reminder;
use App\Models\shared\Team;
use App\Models\shared\Timeline;
use App\Models\shared\Vendor;
use App\Repositories\reminder\HasReminder;
use App\Traits\HasReportDoc;
use App\Traits\HasShared;
use App\Traits\HasTimeline;
use App\Traits\Searchable;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Payrequest extends Model
{
    use HasReminder, HasTimeline, HasReportDoc, HasShared, Searchable;

    protected $searchable = [
        'serial_num', 
        'content', 
        'money_receiver', 
        'bank_account'
    ];

    protected $fillable = [
        'id', 'amount', 'content', 'status', 'finish_date', 'proposer_payment',
        'payrequest_type_id', 'user_id', 'bugget_id', 'team_id', 'company_id', 'method_payment', 'bank_id', 'vendor_id',
        'bank_account', 'contract_id', 'created_at', 'updated_at', 'send_date', 'contract_term_id', 'department_id',
        'bugget_type', 'document_type_id', 'group_id', 'currency',
        'amount_exchanged', 'exchange_rate', 'budget_num', 'budget_type', 'money_receiver', 'doc_reference', 'serial_num', 'miss_invoice', 'payment_type_id',
        'date_receive_hardoc', 'locked', 'bank_branch',
        'bank_name', 'note', 'out_budget', 'amount_out_budget', 'amount_out_exchanged', 'printed', 'teamconfig_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function proposer_payment()
    {
        return $this->belongsTo(User::class, 'proposer_payment', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
    public function payrequest_type()
    {
        return $this->belongsTo(PayrequestType::class);
    }
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
    public function contract_term()
    {
        return $this->belongsTo(ContractTerm::class);
    }

    public function payment_vouchers()
    {
        return $this->hasMany(PaymentVoucher::class)->with('typeObj');
    }
    public function payment_advance_moneys()
    {
        return $this->hasMany(PaymentAdvanceMoney::class);
    }

    public function payment_attacheds()
    {
        return $this->hasMany(PaymentAttached::class);
    }
    public function approveds()
    {
        return $this->morphMany(Approved::class, 'approvedable')->with('user');
    }

    public function reminders()
    {
        return $this->morphMany(Reminder::class, 'reminderable')->with('user');
    }



    public function team()
    {
        return $this->belongsTo(Team::class)->with(['users', 'userccs']);
    }

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class);
    }
    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class);
    }
    public function budgetTypeObj()
    {
        return $this->belongsTo(BudgetType::class, 'budget_type', 'id');
    }
}
