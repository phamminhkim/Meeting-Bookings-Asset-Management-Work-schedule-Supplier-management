<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class DocumentGroup extends Model
{
    protected $fillable = [
        'id', 'name','description','created_at','module','updated_at'
    ];
    public function document_types(){
        return $this->hasMany(DocumentType::class);
    }
}
