<?php

namespace App\Models\payment;

use App\Models\shared\File;
use Illuminate\Database\Eloquent\Model;

//Chứng từ đính kèm
class PaymentAttached extends Model
{
    protected $fillable = ['id','payrequest_id','name','note','checked','paycatset_id','paycattype_id','create_at','update_at'];
    protected $hidden = [
        'create_at', 'update_at',
    ];
    public function attachment_file(){
        return $this->morphMany(File::class,'fileable');
    }

}
