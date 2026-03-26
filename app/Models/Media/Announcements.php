<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Announcements extends Model
{
    use HasFactory;
    
    protected $table = 'announcements';

    protected $fillable = [
        'title',
        'date',
        'category',
        'priority',
        'summary',
        'details'
    ];
}
