<?php

namespace App\Models\event;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Event extends Model
{
	use HasFactory;
    protected $fillable = ['id','title','start','end','created_at','updated_at'
 
    ];
}
