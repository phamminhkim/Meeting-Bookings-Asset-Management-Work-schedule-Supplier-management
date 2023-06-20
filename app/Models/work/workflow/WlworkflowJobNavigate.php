<?php

namespace App\Models\work\workflow;

use App\User;
use Illuminate\Database\Eloquent\Model;

class WlworkflowJobNavigate extends Model
{
    protected $table = 'wlworkflow_jobnavigates';

    protected $fillable = [
        'id', 'job_id','navigate_job_id' ];
}


