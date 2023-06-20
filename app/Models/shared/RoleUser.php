<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    public $table = "role_user";
    protected $primaryKey = [
        'role_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
