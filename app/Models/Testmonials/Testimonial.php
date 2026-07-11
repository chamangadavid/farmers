<?php

namespace App\Models\Testmonials;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Testimonial extends Model
{
    
   use HasFactory;

    protected $fillable = [

        'name',
        'location',
        'image',
        'review',
        'status'
    ];

}
