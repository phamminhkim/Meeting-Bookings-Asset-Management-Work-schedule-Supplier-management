<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;
use App\Models\shared\Department;
use App\Models\shared\Company;
class Group extends Model
{
    protected $fillable = ['id', 'name','description','module','active','company_id','department_id','workshop_id','party_id','created_at','updated_at'];
	public function users()
    {
        return $this->belongsToMany('App\User','group_user');
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function department(){
        
        return $this->belongsTo(Department::class);
    }
    public function workshop(){
        
        return $this->belongsTo(Workshop::class);
    }
    public function party(){
        
        return $this->belongsTo(Party::class);
    }
}
