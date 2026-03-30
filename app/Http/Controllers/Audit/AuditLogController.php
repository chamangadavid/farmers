<?php

namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use App\Models\Audit\AuditLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuditLogController extends Controller
{

 public function getAudits()
    {
        return Inertia::render('MyAAIB/Audits/Index');
    }

    // public function index()
    // {
    //     return AuditLog::latest()->get();
    // }

    public function index()
{
    return AuditLog::with('user')->latest()->get();
}

public function showPage($id)
{
    $audit = AuditLog::with('user')->findOrFail($id);

    return Inertia::render('MyAAIB/Audits/ViewTrailAudits', [
        'audit' => $audit
    ]);
}

    public function show($id)
    {
        return AuditLog::findOrFail($id);
    }
}
