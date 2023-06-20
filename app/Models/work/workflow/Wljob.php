<?php

namespace App\Models\work\workflow;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Wljob extends Model
{
    protected $table = 'wlworkflow_jobs';

    protected $fillable = [
        'id', 'unique_name', 'name', 'scores', 'job_type_id', 'note',
        'wlphase_id', 'time_execution', 'allow_admin_withdraw_job', 'allow_user_withdraw_job', 'allow_abandon_job', 'allow_self_assign_job', 'allow_admin_assign_user', 'allow_user_assign_another_user',
        'action_user', 'assign_user', 'description', 'created_at', 'updated_at', 'is_action_user_by_ref', 'action_user_path_ref', 'is_assign_user_by_ref', 'assign_user_path_ref',
        'date_received', 'date_expected', 'date_finished', 'private_job', 'required_job', 'is_completed', 'is_denied', 'is_navigated'
    ];

    protected $casts = [
        'is_denied' => 'boolean',
        'is_completed' => 'boolean',
        'is_navigated' => 'boolean',
        'is_action_user_by_ref' => 'boolean',
        'is_assign_user_by_ref' => 'boolean'
    ];

    public function wldataobjects()
    {
        return $this->hasMany(Wldataobject::class)->orderBy('order');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'action_user');
    }
    public function assigning_user()
    {
        return $this->hasOne(User::class, 'id', 'assign_user');
    }
    public function job_type()
    {
        return $this->hasOne(WlworkflowJobType::class, 'id', 'job_type_id');
    }
    public function wlphase()
    {
        return $this->belongsTo(Wlphase::class);
    }
    public function dependencies()
    {
        return $this->hasMany(WljobDependency::class, 'jobid', 'id');
    }
    public function navigates()
    {
        return $this->hasMany(WlworkflowJobNavigate::class, 'job_id', 'id');
    }
    public function navigated_by_job()
    {
        return $this->hasOne(WlworkflowJobNavigate::class, 'navigate_job_id', 'id');
    }
    public function details()
    {
        return $this->hasMany(WlworkflowJobDetail::class, 'job_id', 'id')->orderBy('updated_at', 'DESC');
    }
    public function configs()
    {
        return $this->morphMany(WlworkflowConfig::class, 'objectable');
    }
}
