<?php

namespace App\Traits;

use App\Models\shared\Role;
use App\Models\shared\RoleUser;

trait HasPermissions
{
    protected $permissionList = null;
    // protected $slocList = null;
    // protected $companyList = null;
    public function roles()
    {
        return $this->belongsToMany('App\Models\shared\Role', 'App\Models\shared\RoleUser', 'user_id', 'role_id');
    }
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contain('name', $role);
        }
        return false;
    }
    public function hasWorkflowPermission($workflow_code, $permission = null) {
        if (is_string($permission)) {
            return $this->hasPermission($permission . '_workflow_' . $workflow_code);
        }
        return false;
    }
    
    public function hasPermission($permission = null)
    {
        if (is_string($permission)) {
            return $this->getPermission()->contains('name', $permission);
        }
        return false;
    }
    private  function getPermission()
    {
        $role = $this->roles->first();
        $this->roles = $this->roles->where('active', '1');
        if ($role) {
            if (!$role->relationLoaded('permissions')) {
                $this->roles->load('permissions');
            }
            $this->permissionList = $this->roles->pluck('permissions')->flatten();
        }
        return $this->permissionList ?? collect();
    }
    // public function hasSloc($sloc = null)
    // {

    //     if (is_string($sloc)) {
    //         return $this->getSloc()->contains('name', $sloc);
    //     }
    //     return false;
    // }
   
    // private  function getSloc()
    // {
    //     $role = $this->roles->first();
    //     $this->roles = $this->roles->where('active', '1');
    //     if ($role) {
    //         if (!$role->relationLoaded('slocs')) {
    //             $this->roles->load('slocs');
    //         }
    //         $this->slocList = $this->roles->pluck('slocs')->flatten();
    //     }
    //     return $this->slocList ?? collect();
    // }
    // public function hasCompany($companyid = null)
    // {

    //     if (is_string($companyid)) {
    //         return $this->getCompany()->contains('id', $companyid);
    //     }
    //     return false;
    // }
    // private  function getCompany()
    // {
    //     $role = $this->roles->first();
    //     $this->roles = $this->roles->where('active', '1');
    //     if ($role) {
    //         if (!$role->relationLoaded('slocs')) {
    //             $this->roles->load('slocs');
    //         }
    //         $this->slocList = $this->roles->pluck('slocs')->flatten();
    //     }
    //     return $this->slocList ?? collect();
    // }
}
