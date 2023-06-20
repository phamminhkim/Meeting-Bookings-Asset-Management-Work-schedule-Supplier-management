<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;
use App\Models\shared\Company;
use App\Models\shared\Group;

class Party extends Model
{
    protected $fillable = [
        'id', 'company_id', 'department_id','workshop_id', 'code', 'name', 'created_at', 'updated_at'
    ];
    public function company()
    {
        return $this->hasOne(Company::class);
    }
    public function department()
    {
        return $this->hasOne(Department::class);
    }
    public function workshop()
    {
        return $this->hasOne(Workshop::class);
    }
}
