<?php

namespace App\Models\shared;

use App\Traits\HasAttachmentFile;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{

    use HasAttachmentFile;
    protected $fillable = ['id','reminderable_id','reminderable_type','content','date_due','start_date','type_id','duration','duration_value','reminded_before_day','active','url', 'print'];

    public function reminderable(){
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
