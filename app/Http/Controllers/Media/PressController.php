<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PressController extends Controller
{
    
    public function GetPressReleases()
    {
        return Inertia::render('MyAAIB/Press/Index');
    }
}
