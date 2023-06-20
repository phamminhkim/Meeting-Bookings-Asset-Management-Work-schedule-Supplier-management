<?php

namespace App\Models\shared;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'name', 'id'
    ];
    protected $casts = [
        'id' => 'string'
    ];

    public function document_type()
    {
        return $this->hasMany(DocumentType::class);
    }
}
