<?php

namespace App\Models\productivity;

use Illuminate\Database\Eloquent\Model;

class HrproductivityMark extends Model
{
    protected $fillable = [
        'id',
        'record_type',
        'company_id',
        'department_id',
        'workshop_id',
        'party_id',
        'staff_code',
        'year',
        'month',
        'day',
        'value',
        'type_rank',
        'date',
        'create_at',
        'update_at',
        'update_by',
        
    ];
    public function updateBy()
    {
        return $this->belongsTo(User::class,'update_by');
    }
    public function productivityStaff()
    {
        return $this->belongsTo(HrproductivityStaff::class);
    }
    
}
