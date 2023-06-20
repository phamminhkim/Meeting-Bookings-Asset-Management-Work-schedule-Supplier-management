<?php

namespace App\Models\productivity;

use App\Models\shared\Employee;
use App\Repositories\reminder\HasReminder;
use App\Traits\HasAttachmentFile;
use App\User;
use Illuminate\Database\Eloquent\Model;

class HraddMark extends Model
{
    use HasReminder,HasAttachmentFile;
    protected $fillable = ['id','staff_code','user_id','year','month','mark_delta','reason','company_id','create_at','update_at'];

    public function employee()
    {
        return $this->belongsTo(Employee::class,'staff_code','employee_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
