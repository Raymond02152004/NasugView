<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessOwnerAccount extends Model
{
    protected $table = 'businessowneraccount';
    protected $primaryKey = 'business_id';
    public $timestamps = false;

    protected $fillable = [
        'business_name',
        'business_type',
        'business_address',
        'email'
    ];
}
