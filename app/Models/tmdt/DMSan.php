<?php

namespace App\Models\tmdt;

use Illuminate\Database\Eloquent\Model;

class DMSan extends Model
{
    public $table = "tmdt_dm_san";
    protected $primaryKey  = 'Ma';
    public $incrementing = false;
    public $fillable = ['Ma', 'Ten'];
}
