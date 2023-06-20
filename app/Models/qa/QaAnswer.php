<?php

namespace App\Models\qa;

use Illuminate\Database\Eloquent\Model;
use App\User;

class QaAnswer extends Model
{
    protected $fillable = ['id', 'user_id', 'question_id', 'content', 'approved'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function question()
    {
        return $this->belongsTo(QaQuestion::class);
    }

    public function tag()
    {

        return $this->belongsTo(QaQuestionTag::class,'question_id');
    }

    public function tags()
    {

        return $this->hasMany(QaQuestionTag::class,'question_id');
    }


   
}
