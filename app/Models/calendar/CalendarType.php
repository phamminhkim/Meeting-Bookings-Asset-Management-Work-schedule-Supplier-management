<?php

namespace App\Models\calendar;

use Illuminate\Database\Eloquent\Model;

class CalendarType extends Model
{
    protected $fillable = [
        'id', 'name','code'
    ];
}
