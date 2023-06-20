<?php

namespace App\Models\work\workflow;

use Illuminate\Database\Eloquent\Model;
use App\Models\shared\Team;
use App\Traits\HasScope;
use App\Traits\HasConfig;

class Wlworkflow extends Model
{
    use HasScope, HasConfig;

    protected $fillable = [
        'id', 'name', 'original_id', 'wlworkflow_type_id', 'description', 'active', 'created_at', 'updated_at', 'type', 'approve_type', 'approve_team',
        'sub_approve_type', 'sub_approve_value', 'default_group', 'config_id'
    ];

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
    public function wlworkflow_type()
    {
        return $this->belongsTo(WlworkflowType::class);
    }
    public function wlphase()
    {
        return $this->hasMany(Wlphase::class);
    }
    public function wldataobjects()
    {
        return $this->hasMany(Wldataobject::class)->orderBy('order');
    }
    public function wldoctypes()
    {
        return $this->hasMany(Wldoctype::class);
    }
    public function currentPhase()
    {
        return $this->belongsTo(Wlphase::class, 'current_phase', 'id');
    }
    public function finishedPhase()
    {
        return $this->belongsTo(Wlphase::class, 'finished_phase', 'id');
    }
    public function team()
    {
        return $this->belongsTo(Team::class, 'approve_team', 'id');
    }
}
