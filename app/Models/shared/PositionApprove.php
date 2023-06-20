<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;
use App\User;
class PositionApprove extends Model
{
    protected $fillable = ['id', 'user_id','position_id','mask','description','active','created_at','updated_at'];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function position(){
        return $this->belongsTo(Position::class);
    }
}
