<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class MailLog extends Model
{
    public $table = 'email_logs';
    public $fillable = ['id', 'date','from','to','cc','bcc','subject','body','headers','attachments','created_at','updated_at','resend','hash','sentsuccess'];
}
