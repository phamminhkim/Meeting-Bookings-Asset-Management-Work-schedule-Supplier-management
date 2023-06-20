<?php

namespace App\Models\sloc;

use App\Models\asset\AssetTool;
use App\Models\asset\AssetWarehouse;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\shared\Sloc;


class Gooddocu extends Model
{
    protected $fillable = ['id','sloc_id','serial_num','shipper_name','user_id','type','reason'];
    
    public function goods()
    {
        return $this->belongsTo(GooddocusDetail::class);
    }
    // public function goodetails(){
    //     return $this->hasMany(GooddocusDetail::class);
    // }
    public function GooddocusDetails(){
        return $this->hasMany(GooddocusDetail::class);
    }
    public function warehouses(){
        return $this->belongsTo(AssetWarehouse::class);
    }
    public function Asset()
    {
        return $this->belongsTo(Asset::class);
    }
    public function AssetTool()
    {
        return $this->belongsTo(AssetTool::class);
    }
   
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function goodunit()
    {
        return $this->belongsTo(Goodunits::class);
    }
    public function sloc(){
        return $this->belongsTo(Sloc::class);
    }
    

    
    
}
