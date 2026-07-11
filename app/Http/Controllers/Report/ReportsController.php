<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportsController extends Controller
{
    
    public function GetReports()
    {
        return Inertia::render('MyFarmer/Reports/Index');
    }
}
