<?php

namespace App\Models\work\workflow;

use App\User;
use Illuminate\Database\Eloquent\Model;

class WljobDependency extends Model
{
    protected $table = 'wlworkflow_jobdepends';

    protected $fillable = [
        'id', 'jobid','dependjobid' ];
}


