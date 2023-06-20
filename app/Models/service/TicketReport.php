<?php

namespace App\Models\service;

use Illuminate\Database\Eloquent\Model;

class TicketReport extends Model
{
    protected $fillable = ['id', 'ticket_id', 'user_id', 'content','created_at','updated_at'];
}
