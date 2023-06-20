<?php

namespace App\Models\car;

use Illuminate\Database\Eloquent\Model;

class MonitorImplementation extends Model
{
    protected $fillable = [
        'id', 'car_id', 'cause_measure_id', 'result', 'date', 'finished_date', 'create_at', 'update_at'
    ];
    protected $hidden = [
        'create_at', 'update_at',
    ];
}
