<?php

namespace App\Models\productivity;

use Illuminate\Database\Eloquent\Model;

class HrproductivityStaff extends Model
{
    protected $fillable = ['id','year','month','staff_code','totalday_calc','update_by','create_at','update_at'];
    // public function user()
    // {
    //     return $this->belongsTo(User::class,'staff_id');
    // }
    public function updateBy()
    {
        return $this->belongsTo(User::class,'update_by');
    }
    // public function productivityDoc()
    // {
    //     return $this->belongsTo(HrproductivityDoc::class);
    // }
    public function productivityMarks()
    {
        return $this->hasMany(HrproductivityMark::class);
    }
    

}
