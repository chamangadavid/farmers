<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Management\Team;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeamController extends Controller
{
    
    public function managementTeam()
    {
        return Inertia::render('MyAAIB/Management/Index');
    }

     public function managementTeamDetails()
    {
        return Inertia::render('MyAAIB/Management/Index');
    }

    public function index()
    {
        $members = Team::orderBy('created_at', 'desc')->get();
        return response()->json(['members' => $members]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->only(['fullname', 'position', 'content']);
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('management-team', 'public');
            $data['image'] = $path;
        }

        $member = Team::create($data);

        return response()->json(['success' => true, 'member' => $member]);
    }

    public function update(Request $request, $id)
    {
        $member = Team::findOrFail($id);

        $request->validate([
            'fullname' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->only(['fullname', 'position', 'content']);
        
        if ($request->hasFile('image')) {
            // Delete old image
            if ($member->image) {
                Storage::disk('public')->delete($member->image);
            }
            $path = $request->file('image')->store('management-team', 'public');
            $data['image'] = $path;
        }

        $member->update($data);

        return response()->json(['success' => true, 'member' => $member]);
    }

    public function destroy($id)
    {
        $member = Team::findOrFail($id);
        
        // Delete image if exists
        if ($member->image) {
            Storage::disk('public')->delete($member->image);
        }
        
        $member->delete();

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $member = Team::findOrFail($id);
        return response()->json(['member' => $member]);
    }

}
