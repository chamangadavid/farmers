<?php

namespace App\Http\Controllers\Incident;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IncidentsController extends Controller
{
    public function GetIncidents()
    {
        return Inertia::render('MyAAIB/Incidents/Index');
    }
}
