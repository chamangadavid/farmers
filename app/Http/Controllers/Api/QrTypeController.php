<?php

namespace App\Http\Controllers\Api;

use App\Models\QrType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QrTypeController extends Controller
{

    public function index()
    {
        $types = QrType::where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'slug', 'icon', 'description']);

        return response()->json([
            'success' => true,
            'data' => $types
        ]);
    }

    public function show($slug)
    {
        $type = QrType::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $type
        ]);
    }
    
}
