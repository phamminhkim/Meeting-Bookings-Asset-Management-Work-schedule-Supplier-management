<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class UserVariant extends Model
{
    public $fillable = [
        'id', 'user_id','url','name','properties'
    ];
    protected $casts = [
        'properties' => 'array'
   ];
//    public function setPropertiesAttribute($value)
//     {
//         $list = [];

//         foreach ($value as $key=>$array_item) {

//             if (is_null($array_item)) {
//                 $array_item = "";
//             }
//             $list[] = [$key=>$array_item];
//         }

//         $this->attributes['properties'] = json_encode($list);
//     }


}
