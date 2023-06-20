<?php

namespace App\Models\work\workflow;

use App\User;
use Illuminate\Database\Eloquent\Model;

class WlworkflowJobDetail extends Model
{
    protected $table = 'wlworkflow_jobdetails';

    protected $fillable = ['id', 'job_id', 'user_id', 'content'];
}
