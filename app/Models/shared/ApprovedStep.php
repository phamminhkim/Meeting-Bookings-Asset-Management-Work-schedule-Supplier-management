<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class ApprovedStep extends Model
{
    protected $fillable = [
        'document_type_id', 'id','step','name' ];
    
}
