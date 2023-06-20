<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $table = 'roles';
    public function permissions()
    {
        return $this->belongsToMany('App\Models\shared\Permission', 'role_permission');
    }
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
    public function menus()
    {
        return $this->belongsToMany('App\Models\shared\Menu', 'role_menu');
    }
   
}
