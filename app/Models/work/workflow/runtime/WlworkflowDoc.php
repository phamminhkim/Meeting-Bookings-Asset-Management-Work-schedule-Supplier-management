<?php

namespace App\Models\work\workflow\runtime;

use App\Models\shared\Approved;
use App\Models\shared\Company;
use App\Models\shared\Department;
use App\Models\shared\DocumentType;
use App\Models\shared\Group;
use App\Models\shared\Reminder;
use App\Models\shared\Team;
use App\Models\work\workflow\Wldataobject;
use App\Models\work\workflow\Wlworkflow;
use App\Repositories\reminder\HasReminder;
use App\Traits\HasAttachmentFile;
use App\Traits\HasReportDoc;
use App\Traits\HasShared;
use App\Traits\HasTimeline;
use App\User;
use Illuminate\Database\Eloquent\Model;

class WlworkflowDoc extends Model
{
    use HasReminder,HasAttachmentFile,HasTimeline,HasShared,HasReportDoc;
    protected $fillable = ['id','title','original_id','serial_num','finished','wlworkflow_id',
    'status','company_id','department_id','user_id','group_id','document_type_id',
    'team_id','finished','create_at','update_at','wlworkflow_type_id'];

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
    public function reminders(){
        return $this->morphMany(Reminder::class,'reminderable')->with('user','attachment_file');
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
    public function wlworkflow(){
        return $this->belongsTo(Wlworkflow::class);
    }
    public function approveds(){
        return $this->morphMany(Approved::class,'approvedable')->with('user');
    }


}
