<?php

namespace App\Models\document;

use App\Models\shared\Department;
use App\Models\shared\Approved;
use App\Models\shared\BudgetType;
use App\Models\shared\Company;
use App\Models\shared\DocumentType;
use App\Models\shared\File;
use App\Models\shared\Filesign;
use App\Models\shared\Group;
use App\Models\shared\PaymentType;
use App\Models\shared\Reminder;
use App\Models\shared\Team;
use App\Repositories\reminder\HasReminder;
use App\Traits\HasAttachmentFile;
use App\Traits\HasReportDoc;
use App\Traits\HasShared;
use App\Traits\HasTimeline;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasReminder,HasAttachmentFile,HasTimeline,HasShared,HasReportDoc;

    protected $fillable = ['id','title','content','amount','budget_type','currency','serial_num','budget_num',
    'status','company_id','department_id','payment_type_id','user_id','document_type_id','group_id',
    'team_id','finished','create_at','update_at','doc_type_id','locked','docbrowser_type_id','teamconfig_id'];
    public function user()
    {
        return $this->belongsTo(User::class)->with('company');
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function department(){
        return $this->belongsTo(Department::class);
    }
    public function approveds(){
        return $this->morphMany(Approved::class,'approvedable')->with('user');
    }

    public function reminders(){
        return $this->morphMany(Reminder::class,'reminderable')->with('user','attachment_file');
    }
    public function document_type(){
        return $this->belongsTo(DocumentType::class);
    }
    public function group(){
        return $this->belongsTo(Group::class);
    }

    public function doc_type(){
        return $this->belongsTo(DocType::class);
    }

    public function team(){
        return $this->belongsTo(Team::class);
    }

    public function payment_type(){
        return $this->belongsTo(PaymentType::class);
    }
    public function budgetTypeObj(){
        return $this->belongsTo(BudgetType::class,'budget_type','id');
    }
    public function docbrowser_type(){
        return $this->belongsTo(DocbrowserType::class);
    }
    public function filesigns(){
        return $this->morphMany(Filesign::class,'objectable');
    }




}
