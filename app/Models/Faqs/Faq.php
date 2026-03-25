<?php

namespace App\Models\Faqs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;
    
    protected $table = 'faqs';

    protected $fillable = [
        'category',
        'question',
        'answer',
    ];
}
