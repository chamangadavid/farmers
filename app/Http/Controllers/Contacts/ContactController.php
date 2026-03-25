<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function ContactUs()
    {
        return Inertia::render('MyAAIB/Contacts/Index');
    }
}
