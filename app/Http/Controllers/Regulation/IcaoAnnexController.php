<?php

namespace App\Http\Controllers\Regulation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IcaoAnnexController extends Controller
{
    
    public function GetNationalRegulations()
    {
        return Inertia::render('MyAAIB/Regulations/IcaoAnnexs');
    }
}
