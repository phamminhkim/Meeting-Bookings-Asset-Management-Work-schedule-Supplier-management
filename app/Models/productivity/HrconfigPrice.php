<?php

namespace App\Models\productivity;

use Illuminate\Database\Eloquent\Model;

class HrconfigPrice extends Model
{
    protected $fillable = ['id','company_id','type_rank','value','create_at','update_at'];
}
