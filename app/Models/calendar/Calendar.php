<?php

namespace App\Models\calendar;

use App\Models\shared\Company;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $fillable = ['id','company_id','calendar_type_id','year','title','description'];
    
    public function calendars()
    {
        return $this->belongsTo(CalendarDetail::class);
    }
    public function CalendarDetails(){
        return $this->hasMany(CalendarDetail::class);
    }
   
    public function CalendarType()
    {
        return $this->belongsTo(CalendarType::class);
    }
    public function calendarholiday()
    {
        return $this->belongsTo(CalendarHoliday::class);
    }
    public function Company()
    {
        return $this->belongsTo(Company::class);
    }
    
}
