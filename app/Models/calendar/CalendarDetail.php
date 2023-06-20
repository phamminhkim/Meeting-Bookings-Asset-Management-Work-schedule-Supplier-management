<?php

namespace App\Models\calendar;

use Illuminate\Database\Eloquent\Model;

class CalendarDetail extends Model
{
    protected $table='calendar_details';
    protected $fillable = ['id','calendar_id','month','year','day','work','calendar_holiday_id'];
    public function calendarholiday()
    {
        return $this->belongsTo(CalendarHoliday::class);
    }
    
   
}
