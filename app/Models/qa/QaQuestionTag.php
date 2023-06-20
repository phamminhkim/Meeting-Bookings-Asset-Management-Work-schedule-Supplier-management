<?php

namespace App\Models\qa;

use Illuminate\Database\Eloquent\Model;

class QaQuestionTag extends Model
{
    protected $fillable = ['id', 'question_id', 'tag_id'];

    public function question()
    {
        return $this->belongsTo(QaQuestion::class);
    }

    public function tag()
    {
        return $this->belongsTo(QaTag::class);
    }
}
