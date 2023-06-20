<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class ResignData extends Model
{
    protected $fillable = [
        'staff_id', 'resign_type', 'estimate_resigntime',
        'is_resigned', 'official_resigntime', 'is_available'
    ];

    protected $hidden = [
        'updated_at', 'created_at'
    ];

    public function staff()
    {
        return $this->belongsTo(Employee::class, 'staff_id');
    }

    public function resign_type()
    {
        return $this->hasOne(ResignType::class, 'id', 'resign_type');
    }
}
