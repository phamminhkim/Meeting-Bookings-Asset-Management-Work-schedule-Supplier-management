<?php

namespace App\Models\sloc;
use App\Models\service\ServiceCategory;

use Illuminate\Database\Eloquent\Model;

class Goodtypes extends Model
{
    protected $fillable = [
        'id', 'name', 'description', 'code', 'create_at', 'update_at'
    ];
    public function users()
    {
        return $this->belongsToMany('App\User','user_team')->withPivot(['level','step','review','sign'])->orderBy('pivot_step')->orderBy('pivot_level');
    }
    public function userccs()
    {
        return $this->belongsToMany('App\User','usercc_team');
    }
 
}
