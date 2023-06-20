<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;
use App\Models\service\Ticket;
use App\User;
class UserTeam extends Model
{

	public $table = 'user_team';
	public function ticket(){
        return $this->hasMany(Ticket::class,'team_id');
		}
	public function users()
    {
        return $this->hasMany(User::class,'id');
    }
}
