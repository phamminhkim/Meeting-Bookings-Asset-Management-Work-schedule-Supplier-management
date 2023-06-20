<?php

namespace App\Models\sloc;
use App\Models\shared\Company;
use App\User;
use App\Models\shared\File;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    protected $fillable = ['id','code','name','description','goodunit_id','size','color','weight','open_stock'];
    public $table = 'goods';
    public function Goodunits(){
        return $this->hasOne(Goodunits::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class)->with('goodunits');
    }
    public function attachment_file(){
        return $this->morphMany(File::class,'fileable');
    }
}
