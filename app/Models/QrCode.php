<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
     protected $fillable = [
        'user_id', 
        'name', 
        'type', 
        'data', 
        'settings', 
        'file_path', 
        'is_dynamic'];
    
    protected $casts = [
        'data' => 'array',
        'settings' => 'array'
    ];
}
