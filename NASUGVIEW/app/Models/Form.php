<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $primaryKey = 'form_id';
    public $timestamps = false;

    protected $fillable = ['title', 'description'];

    public function questions()
    {
        return $this->hasMany(Question::class, 'form_id', 'form_id');
    }

    public function responses()
    {
        return $this->hasMany(Response::class, 'form_id', 'form_id');
    }
}
