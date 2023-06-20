<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class UserCCTeam extends Model
{
    public $table = 'usercc_team';
    public $timestamps = false;
    public $fillable = [
        'user_id', 'team_id',
    ];
    public $primaryKey = ['user_id', 'team_id'];
    public $incrementing = false;

    public function users()
    {
        return $this->hasMany(User::class,'id','user_id');

        
    }
}
