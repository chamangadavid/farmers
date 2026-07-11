<?php

namespace App\Http\Controllers\Testimonials;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Testmonials\Testimonial;
use Inertia\Inertia;

class TestimonyController extends Controller
{
    
    public function GetTestimonies()
    {
        return Inertia::render('MyFarmer/Testimonials/Index');
    }

     public function indexPage()
    {
        return Inertia::render('MyFarmer/Testimonials/Index');
    }

    /**
     * List testimonials
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $testimonials = Testimonial::query()->when($search, function ($query) use ($search) {

            $query->where('name', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%")
                ->orWhere('review', 'like', "%{$search}%");

        })
        ->latest()
        ->get();

        return response()->json([
            'testimonials' => $testimonials
        ]);
    }

    /**
     * Store
     */
    public function store(Request $request)
    {
        $request->validate([

            'name' => 'required|max:255',

            'location' => 'required|max:255',

            'review' => 'required',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'status' => 'boolean'

        ]);

        $image = null;

        if ($request->hasFile('image')) {

            $image = $request
                ->file('image')
                ->store('testimonials', 'public');

        }

        Testimonial::create([

            'name' => $request->name,

            'location' => $request->location,

            'review' => $request->review,

            'status' => $request->status ?? true,

            'image' => $image

        ]);

        return response()->json([
            'message' => 'Testimonial created successfully.'
        ]);
    }

    /**
     * Update
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate([

            'name' => 'required|max:255',

            'location' => 'required|max:255',

            'review' => 'required',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'status' => 'boolean'

        ]);

        $image = $testimonial->image;

        if ($request->hasFile('image')) {

            if ($testimonial->image &&
                Storage::disk('public')->exists($testimonial->image)) {

                Storage::disk('public')->delete($testimonial->image);

            }

            $image = $request
                ->file('image')
                ->store('testimonials', 'public');

        }

        $testimonial->update([

            'name' => $request->name,

            'location' => $request->location,

            'review' => $request->review,

            'status' => $request->status,

            'image' => $image

        ]);

        return response()->json([
            'message' => 'Testimonial updated successfully.'
        ]);
    }

    /**
     * Delete
     */
    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->image &&
            Storage::disk('public')->exists($testimonial->image)) {

            Storage::disk('public')->delete($testimonial->image);

        }

        $testimonial->delete();

        return response()->json([
            'message' => 'Testimonial deleted successfully.'
        ]);
    }

    /**
     * View one testimonial
     */
    public function show(Testimonial $testimonial)
    {
        return response()->json($testimonial);
    }

    /**
     * Frontend API
     */
    public function frontendTestimonials()
    {
        $testimonials = Testimonial::query()->where('status', true)
            ->latest()
            ->get()
            ->map(function ($testimonial) {

                return [

                    'id' => $testimonial->id,

                    'name' => $testimonial->name,

                    'location' => $testimonial->location,

                    'review' => $testimonial->review,

                    'image' => $testimonial->image
                        ? asset('storage/' . $testimonial->image)
                        : null

                ];

            });

        return response()->json($testimonials);
    }



}
