<?php

namespace App\Models\qa;

use Illuminate\Database\Eloquent\Model;
use App\User;

class QaQuestion extends Model
{
    protected $fillable = ['id', 'user_id', 'category_id', 'title', 'content', 'status', 'public'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(QaCategory::class);
    }

    public function tags()
    {

        return $this->hasMany(QaQuestionTag::class,'question_id');
    }
}
