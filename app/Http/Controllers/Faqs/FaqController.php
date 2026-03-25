<?php

namespace App\Http\Controllers\Faqs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FaqController extends Controller
{
    public function JobVacancies()
    {
        return Inertia::render('MyAAIB/Faqs/Index');
    }
}
