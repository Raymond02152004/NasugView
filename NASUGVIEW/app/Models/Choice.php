<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    protected $primaryKey = 'choice_id';
    public $timestamps = false;

    protected $fillable = ['question_id', 'choice_text', 'position'];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'question_id');
    }
}
