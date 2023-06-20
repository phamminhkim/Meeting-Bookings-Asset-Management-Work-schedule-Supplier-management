<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = ['id','comp_name','signer','position','tax_code','phone','email','address','fax','contact','company_id'];
    public function company(){
        return $this->hasOne(Company::class);
    }

}
