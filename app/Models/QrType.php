<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrType extends Model
{
    protected $fillable = [
        'name', 
        'slug', 
        'icon', 
        'description', 
        'is_active', 
        'sort_order'
        
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function qrCodes()
    {
        return $this->hasMany(QrCode::class, 'type', 'slug');
    }
}
