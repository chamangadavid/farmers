<?php

namespace App\Http\Controllers\Investigation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvestigationsController extends Controller
{
    
    public function GetInvestigations()
    {
        return Inertia::render('MyAAIB/Investigations/Index');
    }
}
