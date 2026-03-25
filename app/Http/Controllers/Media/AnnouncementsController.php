<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnnouncementsController extends Controller
{
    public function GetAnnouncements()
    {
        return Inertia::render('MyAAIB/Announcements/Index');
    }

}
