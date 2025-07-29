<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $primaryKey = 'answer_id';
    public $timestamps = false;

    protected $fillable = ['response_id', 'question_id', 'choice_id', 'answer_text'];

    public function response()
    {
        return $this->belongsTo(Response::class, 'response_id', 'response_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'question_id');
    }

    public function choice()
    {
        return $this->belongsTo(Choice::class, 'choice_id', 'choice_id');
    }
}
