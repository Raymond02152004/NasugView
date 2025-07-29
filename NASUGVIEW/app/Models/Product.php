<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['business_post_id', 'name', 'description'];

    // Define inverse relationship
    public function businessPost()
    {
        return $this->belongsTo(BusinessPost::class);
    }
}
