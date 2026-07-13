<?php

namespace App\Http\Controllers\Vegetables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VegetableController extends Controller
{
    
    public function GetVegetableIndexPage()
    {
        return Inertia::render('MyFarmer/Vegetables/Index');
    }

}
