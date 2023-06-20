<?php

namespace App\Models\work\workflow;

use Illuminate\Database\Eloquent\Model;

class Wlphase extends Model
{
    protected $fillable = [
        'id', 'unique_name', 'name', 'wlworkflow_id', 'description', 'time_execution',
        'created_at', 'updated_at', 'allow_add_job', 'allow_send_response', 'addOwnerToPhaseAdmins', 'limitJobUserByPhaseMember', 'index', 'finished_date', 'approve_type'
    ];
    protected $casts = [
        'allow_add_job' => 'boolean',
        'allow_send_response' => 'boolean',
        'addOwnerToPhaseAdmins' => 'boolean',
        'limitJobUserByPhaseMember' => 'boolean',
    ];
    public function wljobs()
    {
        return $this->hasMany(Wljob::class);
    }
    public function admins()
    {
        return $this->morphMany(WlworkflowAdmin::class, 'objectable');
    }
    public function members()
    {
        return $this->morphMany(WlworkflowMember::class, 'objectable');
    }
    public function follows()
    {
        return $this->morphMany(WlworkflowFollow::class, 'objectable');
    }
    public function wlworkflow()
    {
        return $this->belongsTo(Wlworkflow::class);
    } 
    public function configs()
    {
        return $this->morphMany(WlworkflowConfig::class, 'objectable');
    }
}
