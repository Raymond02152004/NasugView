<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signup extends Model
{
    use HasFactory;

    protected $table = 'signup';
    protected $primaryKey = 'signup_id';
    public $timestamps = false;

    protected $fillable = [
        'email',
        'username',
        'password',
        'role',
        'profile_pic',
    ];

    public function login()
    {
        return $this->hasOne(Login::class, 'signup_id', 'signup_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'signup_id', 'signup_id');
    }

    public function responses()
    {
        return $this->hasMany(Response::class, 'signup_id');
    }

    public function businessPosts()
{
    return $this->hasMany(BusinessPost::class, 'signup_id');
}
}