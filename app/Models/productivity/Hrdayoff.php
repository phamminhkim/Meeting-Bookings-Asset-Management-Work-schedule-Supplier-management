<?php

namespace App\Models\productivity;

use App\Models\shared\Company;
use App\Models\shared\Employee;
use App\Repositories\reminder\HasReminder;
use App\Traits\HasAttachmentFile;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Hrdayoff extends Model
{
    use HasReminder,HasAttachmentFile;
    protected $fillable = ['id','company_id','year','month','day','staff_code','reason','updated_by','create_at','update_at'];
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
