<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = ['id', 'name','company_id','department_id','description','active','created_at','updated_at'];
    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function department(){
        return $this->belongsTo(Department::class);
    }
}
