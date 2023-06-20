<?php

namespace App\Models\service;

use Illuminate\Database\Eloquent\Model;

class TicketAssign extends Model
{
   protected $fillable = ['id', 'ticket_id', 'assign_to', 'assign_by'];
}
