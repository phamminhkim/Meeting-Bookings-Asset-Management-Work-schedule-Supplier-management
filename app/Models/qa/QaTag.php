<?php

namespace App\Models\qa;

use Illuminate\Database\Eloquent\Model;

class QaTag extends Model
{
    protected $fillable = ['id', 'name'];

    public function questions()
    {
        return $this->belongsToMany(QaQuestion::class, 'qa_question_tags');
    }
}
