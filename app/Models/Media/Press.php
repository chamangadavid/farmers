<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Press extends Model
{
    
    use HasFactory;
    
    protected $table = 'presses';

    protected $fillable = [
        'title',
        'date',
        'summary',
        'pdf'
    ];
}
