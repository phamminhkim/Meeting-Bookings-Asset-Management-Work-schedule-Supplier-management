<?php

namespace App\Models\work\workflow;

use Illuminate\Database\Eloquent\Model;

class WldataobjectItms extends Model
{
    protected $fillable = [
        'id', 'name', 'wldataobject_id','order','value','created_at','updated_at','refer_id' ];
}
