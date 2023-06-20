<?php

namespace App\Models\productivity;

use Illuminate\Database\Eloquent\Model;

class HrconfigType extends Model
{
    protected $fillable = ['id','company_id','from','to','value','create_at','update_at'];
}
