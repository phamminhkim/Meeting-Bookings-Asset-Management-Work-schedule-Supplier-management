<?php

namespace App\Models\calendar;

use Illuminate\Database\Eloquent\Model;

class CalendarHoliday extends Model
{
    protected $fillable = [
        'id', 'name','color'
    ];
}
