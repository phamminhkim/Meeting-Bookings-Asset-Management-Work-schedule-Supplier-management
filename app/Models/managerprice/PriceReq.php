<?php

namespace App\Models\managerprice;

use App\Models\shared\Approved;
use App\Models\shared\Company;
use App\Models\shared\Department;
use App\Models\shared\DocumentType;
use App\Models\shared\File;
use App\Models\shared\Group;
use App\Models\shared\OtherAttached;
use App\Models\shared\Reminder;
use App\Models\shared\Team;
use App\Models\shared\Vendor;
use App\Repositories\reminder\HasReminder;
use App\Traits\HasAttachmentFile;
use App\Traits\HasReportDoc;
use App\Traits\HasShared;
use App\Traits\HasTimeline;
use App\User;
use Illuminate\Database\Eloquent\Model;

class PriceReq extends Model
{
    use HasReminder,HasAttachmentFile,HasTimeline,HasReportDoc,HasShared;
    protected $fillable = ['id','title','effective_date','proposer','user_id','team_id',
    'company_id','department_id','send_date','document_type_id','group_id','serial_num',
    'currency','vendor_id','locked','purchase_note','material_type_name','method_payment_name',
    'contract_num','diff_info','order','another_idea','create_at','update_at',
    'is_order','quantity','teamconfig_id'];
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
    public function vendor(){
        return $this->belongsTo(Vendor::class);
    }
    public function approveds(){
        return $this->morphMany(Approved::class,'approvedable')->with('user');
    }

    public function reminders(){
        return $this->morphMany(Reminder::class,'reminderable')->with('user');
    }
    public function document_type(){
        return $this->belongsTo(DocumentType::class);
    }
    public function group(){
        return $this->belongsTo(Group::class);
    }

    public function team(){
        return $this->belongsTo(Team::class);
    }
    public function other_attacheds(){
        return $this->morphMany(OtherAttached::class,'objectable')->with('user');
    }
    public function attachment_file(){
        return $this->morphMany(File::class,'fileable');
    }
    public function proposer(){
        return $this->belongsTo(User::class,'proposer','id');
    }

    public function products(){
        return $this->hasMany(PriceProduct::class)->with(['details','specs']);
    }
}
