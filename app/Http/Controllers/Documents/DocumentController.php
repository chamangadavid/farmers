<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Models\Documents\Document;
use App\Models\Documents\DocumentFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DocumentController extends Controller
{
    
    public function GetIDocuments()
    {
        return Inertia::render('MyFarmer/Documents/Index');
    }

    public function index()
    {
        return response()->json(
            DocumentFolder::with('documents')->get()
        );
    }

    public function storeFolder(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $folder = DocumentFolder::create($request->only('name'));

        return response()->json($folder);
    }

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'folder_id' => 'required|exists:document_folders,id',
                'file' => 'required|file|max:10240'
            ]);

            $file = $request->file('file');

            if (!$file) {
                return response()->json(['message' => 'No file received'], 422);
            }

            $path = $file->store('documents', 'public');

            $doc = Document::create([
                'folder_id' => $request->folder_id,
                'name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getMimeType(),
                'extension' => $file->getClientOriginalExtension(),
            ]);

            return response()->json($doc);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function rename(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $doc = Document::findOrFail($id);
        $doc->update(['name' => $request->name]);

        return response()->json($doc);
    }

    public function destroy($id)
    {
        $doc = Document::findOrFail($id);

        Storage::disk('public')->delete($doc->file_path);

        $doc->delete();

        return response()->json(['message' => 'Deleted']);
    }

    public function PublicIndex()
    {
        return response()->json(
            DocumentFolder::with('documents')->get()
        );
    }


    
}
