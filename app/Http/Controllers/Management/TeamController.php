<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeamController extends Controller
{
    
    public function managementTeam()
    {
        return Inertia::render('MyAAIB/Management/Index');
    }

}
