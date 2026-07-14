<?php

namespace App\Http\Controllers\Gallaries;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class GalleryController extends Controller
{

    public function indexPage()
    {
        return Inertia::render('MyFarmer/Gallery/Index');
    }

    /**
     * Gallery Page
     */
    // public function index()
    // {
    //     return Inertia::render('MyFarmer/Gallery/Index');
    // }

    /**
     * Fetch Galleries
     */
    public function getGallery(Request $request)
    {
        $gallery = Gallery::with('images')
            ->when($request->search, function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->get();

        return response()->json($gallery);
    }

    /**
     * Store Gallery
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images' => 'required|array|min:1',
            'images.*' => 'image|max:5048',
        ]);

        $gallery = Gallery::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => true,
        ]);

        foreach ($request->file('images') as $image) {

            GalleryImage::create([
                'gallery_id' => $gallery->id,
                'image' => $image->store('gallery', 'public'),
            ]);

        }

        return response()->json([
            'message' => 'Gallery created successfully.'
        ]);
    }

    /**
     * Show Gallery
     */
    public function show(Gallery $gallery)
    {
        return response()->json(
            $gallery->load('images')
        );
    }

    /**
     * Update Gallery
     */
    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images.*' => 'image|max:5048',
        ]);

        $gallery->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $image) {

                GalleryImage::create([
                    'gallery_id' => $gallery->id,
                    'image' => $image->store('gallery', 'public'),
                ]);

            }

        }

        return response()->json([
            'message' => 'Gallery updated successfully.'
        ]);
    }

    
    /**
     * Homepage Gallery API
     */
    public function frontendGallery()
    {
        return Gallery::with('images')
            ->where('status', true)
            ->latest()
            ->get()
            ->map(function ($gallery) {

                return [

                    'id' => $gallery->id,

                    'title' => $gallery->title,

                    'description' => $gallery->description,

                    'images' => $gallery->images->map(function ($image) {

                        return asset('storage/' . $image->image);

                    })

                ];

            });
    }

    public function destroy(Gallery $gallery)
    {
        foreach ($gallery->images as $image) {

            if (Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }
        }

        $gallery->delete();

        return response()->json([
            'message' => 'Gallery deleted successfully'
        ]);
    }

    

}