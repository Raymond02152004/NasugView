<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $primaryKey = 'question_id';
    public $timestamps = false;

    protected $fillable = ['form_id', 'question_text', 'question_type', 'position'];

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id', 'form_id');
    }

    public function choices()
    {
        return $this->hasMany(Choice::class, 'question_id', 'question_id');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id', 'question_id');
    }
}
