<?php

namespace App\Models\document;

use Illuminate\Database\Eloquent\Model;

class DocbrowserType extends Model
{
    protected $fillable = [
        'id', 'name','description','active','created_at','module','updated_at'
    ];
    public function document_types(){
        return $this->hasMany(DocumentType::class);
    }
}
