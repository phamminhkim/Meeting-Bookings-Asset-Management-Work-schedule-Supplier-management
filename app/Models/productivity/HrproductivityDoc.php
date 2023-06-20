<?php

namespace App\Models\productivity;

use App\Repositories\reminder\HasReminder;
use App\Models\shared\Approved;
use App\Traits\HasAttachmentFile;
use App\Traits\HasReportDoc;
use App\Traits\HasShared;
use App\Traits\HasTimeline;
use App\User;
use Illuminate\Database\Eloquent\Model;

class HrproductivityDoc extends Model
{
    use HasReminder,HasAttachmentFile,HasTimeline,HasShared,HasReportDoc;

    protected $fillable = ['id','company_id','department_id','workshop_id','party_id','year',
    'month','user_id','create_at','update_at','group_id','team_id','status','document_type_id',
    'send_date','locked','printed','teamconfig_id','serial_num','productivity_type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function department(){
        return $this->belongsTo(Department::class);
    }
    public function group(){
        return $this->belongsTo(Group::class);
    }
    public function approveds(){
        return $this->morphMany(Approved::class,'approvedable')->with('user');
    }

    public function reminders(){
        return $this->morphMany(Reminder::class,'reminderable')->with('user');
    }
    public function team(){
        return $this->belongsTo(Team::class)->with(['users','userccs']);
    }

    public function document_type(){
        return $this->belongsTo(DocumentType::class);
    }
    public function productivityStaffs(){
        return $this->hasMany(HrproductivityStaff::class);
    }
    
}
