<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NewsController extends Controller
{

    public function GetNews()
    {
        return Inertia::render('MyAAIB/News/Index');
    }

}
