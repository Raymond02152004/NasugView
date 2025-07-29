<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessPost extends Model
{
    use HasFactory;

    protected $table = 'business_posts';     // Your table name
    protected $primaryKey = 'business_id';   // Custom primary key

    public $timestamps = true;               // Uses created_at and updated_at

    protected $fillable = [
        'signup_id',
        'business_name',
        'description',
        'contact_info',
        'address',
        'latitude',
        'longitude',
        'image_path',
    ];

    /**
     * Relationship to the Signup model (business owner)
     */
    public function owner()
    {
        return $this->belongsTo(Signup::class, 'signup_id');
    }

    public function menuItems()
    {
        return $this->hasMany(Menu::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
