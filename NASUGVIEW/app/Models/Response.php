<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $primaryKey = 'response_id';
    public $timestamps = false;

    protected $fillable = ['form_id', 'signup_id', 'submitted_at'];

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id', 'form_id');
    }

    public function user()
    {
        return $this->belongsTo(Signup::class, 'signup_id', 'signup_id');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class, 'response_id', 'response_id');
    }
}
