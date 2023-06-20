<?php

namespace App\Models\asset;

use App\Models\shared\Company;
use App\Models\shared\Group;
use Illuminate\Database\Eloquent\Model;
use App\User;


class AssetWarehouse extends Model
{

    protected $fillable = ['id','name','create_by','user_id','phone','address','active','company_id','code','group_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function company(){
        return $this->hasOne(Company::class);
    }
    public function group(){
        return $this->belongsTo(Group::class);
    }
}
