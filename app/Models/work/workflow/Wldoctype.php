<?php

namespace App\Models\work\workflow;

use Illuminate\Database\Eloquent\Model;

class Wldoctype extends Model
{
    protected $fillable = ['id', 'document_type_id','wlworkflow_id','created_at','updated_at' ];

}
