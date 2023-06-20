<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;
use App\Models\service\ServiceCategory;
class Team extends Model
{
    public function servicecates(){
        return $this->morphByMany(ServiceCategory::class,"teamable");
    }
	protected $fillable = ['id', 'name','description','module','active','created_at','updated_at','updated_by'];
	public function users()
    {
        return $this->belongsToMany('App\User','user_team')->withPivot(['level','step','review','sign'])->orderBy('pivot_step')->orderBy('pivot_level');
    }
    public function userccs()
    {
        return $this->belongsToMany('App\User','usercc_team');
    }

}
