<?php

namespace App\Models\car;

use App\Models\shared\CarError;
use App\Models\shared\Company;
use App\Models\shared\Department;
use App\Models\shared\DocumentType;
use App\Models\shared\Group;
use App\Models\shared\OtherAttached;
use App\Models\shared\File;
use App\Models\shared\Reminder;
use App\Models\shared\Wfapproved;
use App\Models\shared\Wfmain;
use App\Models\shared\Wfmainconfig;
use App\Repositories\reminder\HasReminder;
use App\Traits\HasTimeline;
use App\User;
use App\Models\shared\Comment;
use App\Traits\HasShared;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasReminder,HasTimeline,HasShared;
    protected $fillable = [
        'id', 'status', 'issue_date', 'proposer', 'user_id', 'company_id', 'department_id', 'send_date','document_type_id','serial_num', 'locked', 'content', 'issue_count',
        'car_error_id', 'cause', 'risk', 'create_at', 'update_at','group_id','wfmain_id','is_cause_measure','date_to_limit','type_car_id','standard_id'
    ];
    protected $hidden = [
        'create_at', 'update_at',
    ];
    public function user()
    {
        return $this->belongsTo(User::class)->with('company');
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function department()
    {
        return $this->belongsTo(Group::class);
    }
    public function approveds()
    {
        return $this->morphMany(Wfapproved::class, 'wfapprovedable')->with('user');
    }
    public function other_attacheds(){
        return $this->morphMany(OtherAttached::class,'objectable')->with('user');
    }
    public function attachment_file(){
        return $this->morphMany(File::class,'fileable');
    }
    public function reminders()
    {
        return $this->morphMany(Reminder::class, 'reminderable')->with('user');
    }
    public function document_type()
    {
        return $this->belongsTo(DocumentType::class);
    }
    public function immed_action()
    {
        return $this->belongsTo(ImmedAction::class);
    }
    public function group(){
        return $this->belongsTo(Group::class)->with('users');
    }
    public function proposer(){
        return $this->belongsTo(User::class,'proposer','id');
    }
    public function users(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function wfmain(){
        return $this->belongsTo(Wfmain::class);
    }
    public function wfmainconfigables()
    {
        return $this->morphMany(Wfmainconfig::class, 'wfmainconfigable');
    }
    public function cause_measure()
    {
        return $this->hasMany(CauseMeasure::class)->with('monitor_implementation','other_attacheds');//Chỉ đến bảng còn lại
    }
    public function monitor_implementation()
    {
        return $this->hasMany(MonitorImplementation::class);
    }
    public function result_evaluation()
    {
        return $this->hasMany(ResultEvaluation::class)->with('other_attacheds');
    }
    public function fast_process()
    {
        return $this->hasMany(FastProcess::class)->with('other_attacheds');
    }
    public function car_error()
    {
        return $this->belongsTo(CarError::class);
    }
    public function type_car()
    {
        return $this->belongsTo(TypeCar::class);
    }
    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->with('user','other_attacheds','comment_votes');;
    }

}
