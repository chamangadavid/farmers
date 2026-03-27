<?php

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Controller;
use App\Models\Jobs\Job;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{

    public function JobVacancies()
    {
        return Inertia::render('MyAAIB/Jobs/Index');
    }

    public function GetFaqs()
    {
        return Inertia::render('MyAAIB/Faqs/Index');
    }

    // List jobs (show only those that are not expired)
    public function getIndex(Request $request)
    {
        $search = $request->get('search', '');
        $now = now()->setTime(18, 0, 0);

        $jobs = Job::where('deadline', '>', $now)
            ->when($search, function($query, $search) {
                $query->where('title', 'like', "%$search%")
                      ->orWhere('location', 'like', "%$search%");
            })
            ->orderBy('deadline', 'asc')
            ->get();

        return response()->json(['jobs' => $jobs]);
    }

    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $jobs = Job::when($search, function($query, $search) {
                    $query->where('title', 'like', "%$search%")
                        ->orWhere('location', 'like', "%$search%");
                })
                ->orderBy('deadline', 'desc')
                ->get();

        return response()->json(['jobs' => $jobs]);
    }

    // Show a single job
    public function show(Job $job)
    {
        return response()->json(['job' => $job]);
    }

    // Store job
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:Full-time,Part-time,Contract,Temporary,Internship,Other',
            'location' => 'required|string|max:255',
            'deadline' => 'required|date',
            'description' => 'required|string',
            'requirements' => 'nullable|array',
            'pdf_file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('pdf_file')) {
            $data['pdf_file'] = $request->file('pdf_file')->store('jobs', 'public');
        }

        $data['status'] = 'Published'; // default status
        $job = Job::create($data);

        return response()->json(['job' => $job]);
    }

    // Update job
    public function update(Request $request, Job $job)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:Full-time,Part-time,Contract,Temporary,Internship,Other',
            'location' => 'required|string|max:255',
            'deadline' => 'required|date',
            'description' => 'required|string',
            'requirements' => 'nullable|array',
            'pdf_file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('pdf_file')) {
            if ($job->pdf_file) Storage::disk('public')->delete($job->pdf_file);
            $data['pdf_file'] = $request->file('pdf_file')->store('jobs', 'public');
        }

        $job->update($data);

        return response()->json(['job' => $job]);
    }

    // Delete job
    public function destroy(Job $job)
    {
        if ($job->pdf_file) Storage::disk('public')->delete($job->pdf_file);
        $job->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    // Get all jobs that are not expired (deadline today after 18:00 or in the future)
// public function getActiveJobs(Request $request)
// {
//     $now = now()->setTime(18, 0, 0); // today at 18:00

//     $jobs = Job::where(function($query) use ($now) {
//                 $query->where('deadline', '>', $now) // future deadlines
//                       ->orWhere(function($q) use ($now) {
//                           // today but after 18:00 still valid
//                           $q->whereDate('deadline', now()->toDateString())
//                             ->whereTime('deadline', '>=', $now->format('H:i:s'));
//                       });
//             })
//             ->orderBy('deadline', 'asc')
//             ->get();

//     return response()->json(['jobs' => $jobs]);
// }



public function getActiveJobs(Request $request)
{
    $now = now()->setTime(18, 0, 0);

    $jobs = Job::where(function($query) use ($now) {
                $query->where('deadline', '>', $now)
                      ->orWhereDate('deadline', now()->toDateString());
            })
            ->orderBy('deadline', 'asc')
            ->get()
            ->map(function ($job) {
                return [
                    'id' => $job->id,
                    'title' => $job->title,
                    'type' => $job->type,
                    'location' => $job->location,
                    'deadline' => $job->deadline,
                    'description' => $job->description,
                    'requirements' => $job->requirements,
                    'pdf_file' => $job->pdf_file
                        ? asset('storage/' . $job->pdf_file)
                        : null,
                ];
            });

    return response()->json(['jobs' => $jobs]);
}


}
