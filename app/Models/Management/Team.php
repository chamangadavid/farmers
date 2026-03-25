<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{
    use HasFactory;
    
    protected $table = 'teams';

    protected $fillable = [
        'fullname',
        'position',
        'content',
        'image'
    ];
}
