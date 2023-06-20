<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'employee_id', 'company_id', 'department_id', 'workshop_id', 'party_id', 'employee_type', 'employment_type', 'gender',
        'direct_type', 'jobtitle_type', 'name',
        'date_in', 'date_out', 'is_working'
    ];

    protected $hidden = [
        'updated_at', 'created_at'
    ];

    protected $casts = [
        'is_working' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function employee_type()
    {
        return $this->hasOne(EmployeeType::class, 'id', 'employee_type');
    }

    public function employment_type()
    {
        return $this->hasOne(EmployeeType::class, 'id', 'employment_type');
    }

    public function direct_type()
    {
        return $this->hasOne(DirectType::class, 'id', 'direct_type');
    }

    public function jobtitle_type()
    {
        return $this->hasOne(JobTitle::class, 'id', 'jobtitle_type');
    }
}
