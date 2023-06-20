<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;
use App\Models\shared\Company;
use App\Models\shared\Group;

class Workshop extends Model
{
    protected $fillable = [
        'id', 'company_id', 'department_id', 'code', 'name', 'created_at', 'updated_at'
    ];
    public function company()
    {
        return $this->hasOne(Company::class);
    }
    public function department()
    {
        return $this->hasOne(Department::class);
    }
}
