<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportedFile extends Model
{
    protected $table = 'imported_file';
    protected $primaryKey = 'imported_id';
    public $timestamps = false;

    protected $fillable = [
        'filename'
    ];
}
