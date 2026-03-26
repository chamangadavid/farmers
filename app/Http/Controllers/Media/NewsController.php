<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Models\Media\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class NewsController extends Controller
{

    public function GetNews()
    {
        return Inertia::render('MyAAIB/News/Index');
    }

     // GET all
    public function index(Request $request)
    {
        $search = $request->search;

        $news = News::when($search, function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->get();

        return response()->json(['news' => $news]);
    }

    // public function publicNewsIndex()
    // {
    //     $news = News::latest()->get();

    //     return response()->json([
    //         'data' => $news
    //     ]);
    // }

    public function publicNewsIndex()
{
    $news = News::latest()->get()->map(function ($item) {
        if ($item->image) {
            $item->image = asset('storage/' . $item->image);
        }
        return $item;
    });

    return response()->json([
        'data' => $news
    ]);
}

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'date' => 'required|date',
            'category' => 'required',
            'summary' => 'required',
            'author' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->except('image');

        // HANDLE IMAGE PROPERLY
        if ($request->hasFile('image')) {

            $file = $request->file('image');

            // optional: unique name
            $filename = time().'_'.$file->getClientOriginalName();

            // store in public disk
            $path = $file->storeAs('news', $filename, 'public');

            $data['image'] = $path;
        }

        $news = News::create($data);

        return response()->json([
            'message' => 'News created successfully',
            'news' => $news
        ]);
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'date' => 'required|date',
            'category' => 'required',
            'summary' => 'required',
            'author' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->except('image');

        // replace image if new uploaded
        if ($request->hasFile('image')) {

            // delete old image safely
            if ($news->image && Storage::disk('public')->exists($news->image)) {
                Storage::disk('public')->delete($news->image);
            }

            $path = $request->file('image')->store('news', 'public');
            $data['image'] = $path;
        }

        $news->update($data);

        return response()->json([
            'message' => 'Updated successfully',
            'news' => $news
        ]);
    }

    public function destroy($id)
    {
        News::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted']);
    }
    public function show($id)
    {
        $news = News::findOrFail($id);

        return Inertia::render('Site/NewsDetails', [
            'news' => $news
        ]);
    }


}
