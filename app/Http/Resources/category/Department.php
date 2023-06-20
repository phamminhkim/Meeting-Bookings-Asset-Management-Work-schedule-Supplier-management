<?php

namespace App\Http\Resources\category;

use Illuminate\Http\Resources\Json\JsonResource;

class Department extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
        // return[
        //     'id'=> $this->id,
        //     'company_id'=>$this->company_id,
        //     'code'=>$this->code,
        //     'name' =>$this->name
    
        // ];
    }
    //   public function offsetExists($offset) {
    //     return array_key_exists($offset, $this->resource);
    // }
}
