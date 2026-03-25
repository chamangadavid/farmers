<?php

namespace App\Http\Controllers\Accident;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccidentsController extends Controller
{
    public function GetAccidents()
    {
        return Inertia::render('MyAAIB/Accidents/Index');
    }
}
