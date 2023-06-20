<?php

namespace App;

use App\Models\shared\Department;
use App\Models\shared\Company;
use App\Models\shared\Position;
use App\Models\shared\PositionApprove;
use App\Traits\HasPermissions;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\shared\UserTeam;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',  'username', 'sloc_id', 'company_id','department_id','active','manager','gender'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'updated_at', 'created_at', 'deleted_at', 'email_verified_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',

    ];
	public function teams()
    {
        return $this->belongsToMany('App\Models\shared\Team','user_team');
    }
    public function groups()
    {
        return $this->belongsToMany('App\Models\shared\Group','group_user');
    }
    public function roles()
    {
        return $this->belongsToMany('App\Models\shared\Role');
    }
    public function tickets()
    {
        return $this->belongsToMany('App\Models\service\Ticket');
    }
    public function hasAnyRoles($roles)
    {
        if ($this->roles()->whereIn('name', $roles)->first()) {
            return true;
        }
        return false;
    }
    public function hasRole($role)
    {
        if ($this->roles()->where('name', $role)->first()) {
            return true;
        }
        return false;
    }
	public function userTeam()
    {
        return $this->belongsTo(UserTeam::class,'user_id');
    }
    public function manager()
    {
        return $this->belongsTo(User::class,'manager');
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class,'department_id');
    }
    public function position()
    {
        return $this->belongsTo(PositionApprove::class);
    }
}
