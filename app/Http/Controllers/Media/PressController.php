<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Media\Press;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Inertia\Inertia;

class PressController extends Controller
{
    
    public function GetPressReleases()
    {
        return Inertia::render('MyAAIB/Press/Index');
    }

    // GET all
    public function index(Request $request)
    {
        $search = $request->query('search');

        $presses = Press::when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->orderBy('date', 'desc')
            ->get();

        return response()->json(['presses' => $presses]);
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'date' => 'required|date',
            'summary' => 'required',
            'pdf' => 'required|file|mimes:pdf'
        ]);

        $path = $request->file('pdf')->store('press', 'public');

        $press = Press::create([
            'title' => $request->title,
            'date' => $request->date,
            'summary' => $request->summary,
            'pdf' => $path
        ]);

        return response()->json([
            'message' => 'Press created successfully',
            'press' => $press
        ]);
    }

    // SHOW
    public function show($id)
    {
        return response()->json(Press::findOrFail($id));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $press = Press::findOrFail($id);

        if ($request->hasFile('pdf')) {
            if ($press->pdf) {
                Storage::disk('public')->delete($press->pdf);
            }

            $path = $request->file('pdf')->store('press', 'public');
            $press->pdf = $path;
        }

        $press->update([
            'title' => $request->title,
            'date' => $request->date,
            'summary' => $request->summary,
        ]);

        return response()->json([
            'message' => 'Updated successfully',
            'press' => $press
        ]);
    }

    // DELETE
    public function destroy($id)
    {
        $press = Press::findOrFail($id);

        if ($press->pdf) {
            Storage::disk('public')->delete($press->pdf);
        }

        $press->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }

    public function download($id)
    {
        $press = Press::findOrFail($id);

        $filePath = storage_path('app/public/' . $press->pdf);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath);
    }

    public function publicIndex(Request $request)
    {
        $search = $request->query('search');

        $presses = Press::when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%");
            })
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($press) {
                return [
                    'id' => $press->id,
                    'title' => $press->title,
                    'date' => \Carbon\Carbon::parse($press->date)->format('F d, Y'),
                    'summary' => $press->summary,
                    'pdfLink' => asset('storage/' . $press->pdf),
                ];
            });

        return response()->json([
            'presses' => $presses
        ]);
    }



}
