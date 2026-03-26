<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
     use HasFactory;
    
    protected $table = 'news';

    protected $fillable = [
        'title',
        'date',
        'category',
        'summary',
        'author',
        'image',
        'featured',
        'read_time'
    ];
}
