<?php

namespace App\Models\Regulation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NationalRegulations extends Model
{
    use HasFactory;
    
    protected $table = 'national_regulations';

protected $fillable = [
        'title',
        'document_type',
        'regulation_file',
    ];

 

  
}
