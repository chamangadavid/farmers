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

    public function GetVegetableProductionPage()
    {
        return Inertia::render('MyFarmer/Vegetables/VegetableProduction/Index');
    }

    public function GetVegetableHarvestPage()
    {
        return Inertia::render('MyFarmer/Vegetables/Harvests/Index');
    }

    public function GetVegetableSalesPage()
    {
        return Inertia::render('MyFarmer/Vegetables/Sales/Index');
    }

    public function GetVegetableExpensesPage()
    {
        return Inertia::render('MyFarmer/Vegetables/Expenses/Index');
    }


    




}
