<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';
    protected $primaryKey = 'posts_id'; // ✅ Confirm this is correct
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'signup_id',
        'content',
        'media_paths',
        'status',
    ];

    protected $casts = [
        'media_paths' => 'array',
    ];

    public function signup()
    {
        return $this->belongsTo(Signup::class, 'signup_id', 'signup_id');
    }
}

