<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;
use App\Models\shared\Company;
use App\Models\shared\Group;
class Department extends Model
{
    protected $fillable = [
        'id', 'company_id','code','name','created_at','updated_at','department_id'
    ];
    public function company(){
        return $this->hasOne(Company::class);
    }

    public function groups(){
        return $this->hasMany(Group::class);
    }

}
