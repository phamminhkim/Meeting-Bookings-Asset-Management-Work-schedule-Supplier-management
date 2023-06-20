<?php

namespace App\Models\productivity;

use App\Models\shared\Company;
use App\Models\shared\Employee;
use App\Repositories\reminder\HasReminder;
use App\Traits\HasAttachmentFile;
use App\User;
use Illuminate\Database\Eloquent\Model;

class HrsalaryInfo extends Model
{
    use HasReminder,HasAttachmentFile;
    protected $fillable = ['id','staff_code','totalday_calc','totalday_salary','note','create_at','update_at','year','month','updated_by'];
    public function updated_by()
    {
        return $this->belongsTo(User::class,'updated_by');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class,'staff_code','employee_id');
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }
}
