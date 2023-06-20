<?php

namespace App\Models\car;

use Illuminate\Database\Eloquent\Model;

class ImmedAction extends Model
{
    protected $fillable = [
        'id', 'car_id', 'content', 'create_at', 'update_at'
    ];
    protected $hidden = [
        'create_at', 'update_at',
    ];
}
