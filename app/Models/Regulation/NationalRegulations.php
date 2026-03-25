<?php

namespace App\Models\Regulation;

use Illuminate\Database\Eloquent\Model;
use Inertia\Inertia;

class NationalRegulations extends Model
{
    
    public function GetNationalRegulations()
    {
        return Inertia::render('MyAAIB/Regulations/NationalRegulations');
    }
}
