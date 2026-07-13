<?php

namespace App\Http\Controllers\Chickens;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChickenController extends Controller
{
    
    public function GetChickensIndexPage()
    {
        return Inertia::render('MyFarmer/Chickens/Index');
    }


}
