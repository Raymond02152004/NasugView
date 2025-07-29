<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    use HasFactory;

    protected $table = 'login';
    protected $primaryKey = 'login_id';
    public $timestamps = false;

    protected $fillable = [
        'signup_id',
    ];

    public function signup()
    {
        return $this->belongsTo(Signup::class, 'signup_id', 'signup_id');
    }
}