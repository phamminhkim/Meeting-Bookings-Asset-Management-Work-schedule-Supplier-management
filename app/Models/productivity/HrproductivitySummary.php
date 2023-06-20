<?php

namespace App\Models\productivity;

 
use App\Models\shared\Company;
use App\Models\shared\Department;
use App\Models\shared\Party;
use App\Models\shared\Workshop;
use App\User;
use Illuminate\Database\Eloquent\Model;

class HrproductivitySummary extends Model
{
    protected $fillable = ['id','company_id','department_id','workshop_id','party_id','year',
    'month','staff_id','mark_final','type_rank','money_ref','money_final','create_at','update_at'];

    public function employee()
    {
        return $this->belongsTo(Employee::class,'staff_id','employee_id');
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function department(){
        return $this->belongsTo(Department::class);
    }
    public function workshop(){
        return $this->belongsTo(Workshop::class);
    }
    public function party(){
        return $this->belongsTo(Party::class);
    }



}
