<?php

namespace App\Http\Controllers\Regulation;

use App\Http\Controllers\Controller;
use App\Models\Regulation\NationalRegulations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class NationalRegulationsController extends Controller
{

    public function Regulations()
    {
        return Inertia::render('MyAAIB/Regulations/Index');
    }
    
    public function GetNationalRegulations()
    {
        return Inertia::render('MyAAIB/Regulations/NationalRegulations');
    }

    public function index(Request $request)
    {
        $query = NationalRegulations::query();

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        return response()->json([
            'regulations' => $query->latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'title' => 'required|string',
            'document_type' => 'required|string',
            'regulation_file' => 'required|file|mimes:pdf|max:2048',
        ]);

        $filePath = null;

        if ($request->hasFile('regulation_file')) {
            $filePath = $request->file('regulation_file')
                ->store('regulations', 'public');
        }

        NationalRegulations::create([
            'title' => $request->title,
            'document_type' => $request->document_type,
            'regulation_file' => $filePath,
        ]);

        return response()->json(['message' => 'Created']);
    }

    public function update(Request $request, $id)
    {
        
        $regulation = NationalRegulations::findOrFail($id);

        $data = $request->only(['title', 'document_type']);

        if ($request->hasFile('regulation_file')) {

            if ($regulation->regulation_file) {
                Storage::disk('public')->delete($regulation->regulation_file);
            }

            $path = $request->file('regulation_file')->store('regulations', 'public');
            $data['regulation_file'] = $path;
        }

        $regulation->update($data);

        return response()->json(['message' => 'Updated successfully']);
    }
    public function destroy($id)
    {
        $regulation = NationalRegulations::findOrFail($id);

        if ($regulation->regulation_file) {
            Storage::disk('public')->delete($regulation->regulation_file);
        }

        $regulation->delete();

        return response()->json(['message' => 'Deleted']);
    }

    public function publicIndex()
    {
        $regulations = NationalRegulations::latest()->get();

        return response()->json([
            'regulations' => $regulations
        ]);
    }


}
