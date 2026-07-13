<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SalesController extends Controller
{
    
    public function GetSalesIndexPage()
    {
        return Inertia::render('MyFarmer/Sales/Index');
    }
}
