<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Models\Media\Announcements;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnnouncementsController extends Controller
{
    
    public function GetAnnouncements()
    {
        return Inertia::render('MyFarmer/Announcements/Index');
    }

    public function index(Request $request)
    {
        $search = $request->search;

        $announcements = Announcements::query()
                  ->when($search, function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            })
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'announcements' => $announcements
        ]);
    }

    public function publicAnnouncementsIndex(Request $request)
    {
        $search = $request->search;

        $announcements = Announcements::query()
               ->when($search, function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'announcements' => $announcements
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'date' => 'required|date',
            'category' => 'required',
            'priority' => 'required',
            'summary' => 'required',
            'details' => 'required'
        ]);

        $announcement = Announcements::create($request->all());

        return response()->json([
            'message' => 'Announcement created successfully',
            'announcement' => $announcement
        ]);
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcements::findOrFail($id);

        $announcement->update($request->all());

        return response()->json([
            'message' => 'Updated successfully'
        ]);
    }

    public function destroy($id)
    {
        Announcements::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Deleted successfully'
        ]);
    }

}
