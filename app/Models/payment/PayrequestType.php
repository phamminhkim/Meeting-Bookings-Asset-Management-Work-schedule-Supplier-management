<?php

namespace App\Models\payment;

use App\Models\shared\Team;
use Illuminate\Database\Eloquent\Model;

class PayrequestType extends Model
{
    protected $fillable = ['name','id','company_id','created_at','updated_at'];
     
    public function teams()
    {
        return $this->morphToMany(Team::class, 'teamable');
    }
}
