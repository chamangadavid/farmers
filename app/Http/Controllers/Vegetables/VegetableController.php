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

    // public function GetVegetableProductionPage()
    // {
    //     return Inertia::render('MyFarmer/VegetableProduction/Index');
    // }

     public function GetVegetableProductionPage()
    {
        return Inertia::render('MyFarmer/Vegetables/VegetableProduction/Index');
    }

    public function GetVegetableHarvestPage()
    {
        return Inertia::render('MyFarmer/Vegetables/Harvests/Index');
    }



}
