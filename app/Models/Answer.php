<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['form_id', 'question_id', 'respondent_id', 'answer'];

    public function respondent()
    {
        return $this->belongsTo(Respondent::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

}
