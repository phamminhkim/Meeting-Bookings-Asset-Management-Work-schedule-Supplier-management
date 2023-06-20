<?php

namespace App\Models\service;

use Illuminate\Database\Eloquent\Model;
use App\Models\service\Ticket;
use App\Models\shared\Team;
class ServiceCategory extends Model
{
    protected $fillable = ['name','id'];
    public function tickets()
    {
        return $this->belongsToMany(Ticket::class);
    }
    public function teams()
    {
        return $this->morphToMany(Team::class, 'teamable');
    }
}
