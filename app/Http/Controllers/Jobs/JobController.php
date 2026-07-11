<?php

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Controller;
use App\Mail\JobSubmissionMail;
use App\Models\Jobs\Job;
use App\Models\Jobs\JobApplication;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class JobController extends Controller
{

    public function JobVacancies()
    {
        return Inertia::render('MyAAIB/Jobs/Index');
    }

    public function GetFaqs()
    {
        return Inertia::render('MyFarmer/Faqs/Index');
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

    public function details(Job $job)
    {
        return Inertia::render('Site/JobVacanciesDetails', [
            'job' => $job
        ]);
    }

    // public function apply(Request $request)
    // {

    //     try {
    //         $data = $request->validate([
    //             'job_id' => 'required|exists:jobs,id',
    //             'name' => 'required|string',
    //             'email' => 'required|email',
    //             'phone' => 'required|string',
    //             'address' => 'nullable|string',
    //             'current_position' => 'nullable|string',
    //             'current_employer' => 'nullable|string',
    //             'cv' => 'required|file|mimes:pdf|max:2048',
    //             'nrc' => 'required|file|mimes:pdf|max:2048',
    //             'grade12' => 'required|file|mimes:pdf|max:2048',
    //             'degree' => 'nullable|file|mimes:pdf|max:2048',
    //             'masters' => 'nullable|file|mimes:pdf|max:2048',
    //             'other_documents' => 'nullable|file|mimes:pdf|max:2048',
    //         ]);

    //         // Store files
    //         foreach (['cv','nrc','grade12','degree','masters','other_documents'] as $file) {
    //             if ($request->hasFile($file)) {
    //                 $data[$file] = $request->file($file)->store('applications', 'public');
    //             }
    //         }

    //         JobApplication::create($data);

    //         // Send email to admin
    //         $adminEmails = config('mail.admin_addresses.aaib'); // 'umoyoprintex@gmail.com'


    //         return response()->json(['message' => 'Application submitted successfully']);
            
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         return response()->json(['errors' => $e->errors()], 422);
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
    //     }
    // }

      
    public function apply(Request $request)
    {

        try {
            $data = $request->validate([
                'job_id' => 'required|exists:jobs,id',
                'name' => 'required|string',
                'email' => 'required|email',
                'phone' => 'required|string',
                'address' => 'nullable|string',
                'current_position' => 'nullable|string',
                'current_employer' => 'nullable|string',
                'cv' => 'required|file|mimes:pdf|max:2048',
                'nrc' => 'required|file|mimes:pdf|max:2048',
                'grade12' => 'required|file|mimes:pdf|max:2048',
                'degree' => 'nullable|file|mimes:pdf|max:2048',
                'masters' => 'nullable|file|mimes:pdf|max:2048',
                'other_documents' => 'nullable|file|mimes:pdf|max:2048',
            ]);

            // Store files
            foreach (['cv','nrc','grade12','degree','masters','other_documents'] as $file) {
                if ($request->hasFile($file)) {
                    $data[$file] = $request->file($file)->store('applications', 'public');
                }
            }

            JobApplication::create($data);

            // Send email to admin
            $adminEmails = config('mail.admin_addresses.aaib'); // 'umoyoprintex@gmail.com'

            if ($adminEmails) {
                Mail::to($adminEmails)->send(new JobSubmissionMail($data));
                //Mail::to($adminEmails)->send(new JobSubmissionMail($data->toArray()));
            }

            return response()->json(['message' => 'Application submitted successfully']);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }


    public function fetchApplications(Request $request)
    {
        $search = $request->input('search');

        $query = JobApplication::with('job');

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('current_position', 'like', "%{$search}%")
                ->orWhere('current_employer', 'like', "%{$search}%")
                ->orWhereHas('job', function ($q2) use ($search) {
                    $q2->where('title', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            });
        }

        $applications = $query
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($application) {
                return [
                    'id' => $application->id,
                    'job_id' => $application->job_id,
                    'name' => $application->name,
                    'email' => $application->email,
                    'phone' => $application->phone,
                    'address' => $application->address,
                    'current_position' => $application->current_position,
                    'current_employer' => $application->current_employer,
                    'cv' => $application->cv,
                    'nrc' => $application->nrc,
                    'grade12' => $application->grade12,
                    'degree' => $application->degree,
                    'masters' => $application->masters,
                    'other_documents' => $application->other_documents,
                    'created_at' => $application->created_at,
                    'job' => $application->job ? [
                        'id' => $application->job->id,
                        'title' => $application->job->title,
                        'type' => $application->job->type,
                        'location' => $application->job->location,
                    ] : null,
                ];
            });

        return response()->json([
            'applications' => $applications
        ]);
    }

    public function showApplication($id)
    {
        $application = JobApplication::with('job')->findOrFail($id);
        
        return response()->json([
            'application' => [
                'id' => $application->id,
                'job_id' => $application->job_id,
                'name' => $application->name,
                'email' => $application->email,
                'phone' => $application->phone,
                'address' => $application->address,
                'current_position' => $application->current_position,
                'current_employer' => $application->current_employer,
                'cv' => $application->cv,
                'nrc' => $application->nrc,
                'grade12' => $application->grade12,
                'degree' => $application->degree,
                'masters' => $application->masters,
                'other_documents' => $application->other_documents,
                'created_at' => $application->created_at,
                'job' => $application->job ? [
                    'id' => $application->job->id,
                    'title' => $application->job->title,
                    'type' => $application->job->type,
                    'location' => $application->job->location,
                ] : null,
            ]
        ]);
    }

    public function destroyApplication($id)
    {
        $application = JobApplication::findOrFail($id);
        
        // Delete associated files
        $files = ['cv', 'nrc', 'grade12', 'degree', 'masters', 'other_documents'];
        foreach ($files as $file) {
            if ($application->$file && Storage::disk('public')->exists($application->$file)) {
                Storage::disk('public')->delete($application->$file);
            }
        }
        
        $application->delete();
        
        return response()->json(['message' => 'Application deleted successfully']);
    }
    
    public function downloadFile($id, $fileType)
    {
        $application = JobApplication::findOrFail($id);
        $allowedTypes = ['cv', 'nrc', 'grade12', 'degree', 'masters', 'other_documents'];
        
        if (!in_array($fileType, $allowedTypes)) {
            return response()->json(['error' => 'Invalid file type'], 400);
        }
        
        $filePath = $application->$fileType;
        
        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }
        
        return Storage::disk('public')->download($filePath, $application->name . '_' . $fileType . '.pdf');
    }


    // public function fetchApplications()
    // {
    //     $applications = JobApplication::with('job')
    //         ->orderBy('created_at', 'desc')
    //         ->get()
    //         ->map(function ($application) {
    //             return [
    //                 'id' => $application->id,
    //                 'job_id' => $application->job_id,
    //                 'name' => $application->name,
    //                 'email' => $application->email,
    //                 'phone' => $application->phone,
    //                 'address' => $application->address,
    //                 'current_position' => $application->current_position,
    //                 'current_employer' => $application->current_employer,
    //                 'cv' => $application->cv,
    //                 'nrc' => $application->nrc,
    //                 'grade12' => $application->grade12,
    //                 'degree' => $application->degree,
    //                 'masters' => $application->masters,
    //                 'other_documents' => $application->other_documents,
    //                 'created_at' => $application->created_at,
    //                 'job' => $application->job ? [
    //                     'id' => $application->job->id,
    //                     'title' => $application->job->title,
    //                     'type' => $application->job->type,
    //                     'location' => $application->job->location,
    //                 ] : null,
    //             ];
    //         });

    //     return response()->json([
    //         'applications' => $applications
    //     ]);
    // }




}
