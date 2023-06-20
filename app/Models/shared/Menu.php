<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public $table = 'menus';

    public function childs()
    {
        return $this->hasMany('App\Models\shared\Menu', 'parent', 'id')->orderBy('sort');
    }

    public function parents()
    {
        return $this->hasOne('App\Models\shared\Menu', 'id', 'parent');
    }
}
