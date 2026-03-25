<?php

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JobController extends Controller
{
    public function GetFaqs()
    {
        return Inertia::render('MyAAIB/Faqs/Index');
    }
}
