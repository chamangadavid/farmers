<?php

namespace App\Http\Controllers\Accident;

use App\Http\Controllers\Controller;
use App\Models\Accident\AccidentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccidentReportedMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AccidentReportController extends Controller
{

    public function index(Request $request)
    {
        $query = AccidentReport::query();

        //  Search (optional)
        if ($request->search) {
            $query->where('reporter_name', 'like', '%' . $request->search . '%')
                ->orWhere('location', 'like', '%' . $request->search . '%')
                ->orWhere('report_type', 'like', '%' . $request->search . '%');
        }

        $reports = $query->latest()->get();

        return response()->json([
            'reports' => $reports
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reportType' => 'required|in:Accident,Incident,Other',
            'reporterName' => 'required|string|max:255',
            'reporterEmail' => 'required|email',
            'reporterPhone' => 'nullable|string|max:20',

            'accidentDate' => 'required|date',
            'accidentTime' => 'required',

            'location' => 'required|string|max:255',

            'aircraftRegistration' => 'nullable|string|max:100',
            'aircraftType' => 'nullable|string|max:100',

            'description' => 'required|string',
            'injuries' => 'nullable|string',
            'damage' => 'nullable|string',
        ]);

        $report = AccidentReport::create([
            'report_type' => $validated['reportType'],
            'reporter_name' => $validated['reporterName'],
            'reporter_email' => $validated['reporterEmail'],
            'reporter_phone' => $validated['reporterPhone'] ?? null,
            'accident_date' => $validated['accidentDate'],
            'accident_time' => $validated['accidentTime'],
            'location' => $validated['location'],
            'aircraft_registration' => $validated['aircraftRegistration'] ?? null,
            'aircraft_type' => $validated['aircraftType'] ?? null,
            'description' => $validated['description'],
            'injuries' => $validated['injuries'] ?? null,
            'damage' => $validated['damage'] ?? null,

            //'status' => 'Reported', // 
        ]);

        // Send Email
        $adminEmails = config('mail.admin_addresses.aaib');

        if ($adminEmails) {
            Mail::to($adminEmails)->send(new AccidentReportedMail($report->toArray()));
        }



        return response()->json([
            'message' => 'Report submitted successfully',
            'data' => $report
        ], 201);
    }

    public function destroy($id)
    {
        $report = AccidentReport::findOrFail($id);
        $report->delete();

        return response()->json(['message' => 'Deleted']);
    }

    public function resolve(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Reported,Under Investigation,On-Site Investigation,Analysis Ongoing,Preliminary Report,Final Report Pending,Closed',
            'summary' => 'nullable|string',
            'file' => 'nullable|file|max:10240'
        ]);

        $report = AccidentReport::findOrFail($id);

        if ($request->hasFile('file')) {

            $file = $request->file('file');

            // DEBUG
            Log::info('File received', [
                'name' => $file->getClientOriginalName()
            ]);

            $path = $file->store('accident_followups', 'public');

            // DEBUG
            Log::info('File stored at', ['path' => $path]);

            $report->follow_up_file = $path;
        }

        // Save status + summary
        $report->status = $request->status;
        $report->summary = $request->summary;

        $report->save();

        return response()->json([
            'message' => 'Report updated successfully',
            'data' => $report
        ]);
    }

    //shows or display all reports regardless their status
    public function publicReports()
    {
        $reports = AccidentReport::latest()
            ->get()
            ->map(function ($report) {
                return [
                    'id' => $report->id,
                    'title' => trim($report->summary ?? $report->description),
                    'reference' => 'AAIB-' . date('Y', strtotime($report->accident_date)) . '-' . str_pad($report->id, 3, '0', STR_PAD_LEFT),
                    'year' => date('Y', strtotime($report->accident_date)),
                    'date' => date('F d, Y', strtotime($report->accident_date)),
                    'status' => $report->status,
                    'type' => $report->report_type,
                    'file_url' => $report->follow_up_file 
                        ? asset('storage/' . $report->follow_up_file) 
                        : null,
                    'summary' => trim($report->summary ?? $report->description),
                ];
            });

        return response()->json([
            'reports' => $reports
        ]);
    }

    //only show closed, uder investigation where follow_up_file exist
    // public function publicReports()
    // {
    //     $reports = AccidentReport::whereNotNull('follow_up_file') // only published reports
    //         //->where('status', 'Closed') // only completed investigations
    //         ->whereIn('status', ['Closed', 'Under Investigation', 'Preliminary Report','Final Report Pending']) //only show report that closed, under investigation, preliminery report, final report
    //         ->latest()
    //         ->get()
    //         ->map(function ($report) {
    //             return [
    //                 'id' => $report->id,
    //                 'title' => $report->summary ?? $report->description,
    //                 'reference' => 'AAIB-' . date('Y', strtotime($report->accident_date)) . '-' . str_pad($report->id, 3, '0', STR_PAD_LEFT),
    //                 'year' => date('Y', strtotime($report->accident_date)),
    //                 'date' => date('F d, Y', strtotime($report->accident_date)),
    //                 'status' => $report->status,
    //                 'type' => $report->report_type,
    //                 'file_url' => $report->follow_up_file 
    //                     ? asset('storage/' . $report->follow_up_file) 
    //                     : null,
    //                 'summary' => $report->summary
    //             ];
    //         });

    //     return response()->json([
    //         'reports' => $reports
    //     ]);
    // }

    public function search(Request $request)
    {
        $query = $request->query('q'); // search term
        $type = $request->query('type'); // 'accident' or 'incident'

        $reports = AccidentReport::query();

        // Filter by type if provided
        if ($type && in_array($type, ['Accident', 'Incident'])) {
            $reports->where('report_type', $type);
        }

        // Search by title, description, location
        if ($query) {
            $reports->where(function($q) use ($query) {
                $q->where('summary', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                 ->orWhere('report_type', 'like', "%{$query}%")
                  ->orWhere('accident_date', 'like', "%{$query}%")
                  ->orWhere('injuries', 'like', "%{$query}%")
                    ->orWhere('damage', 'like', "%{$query}%")
                      ->orWhere('status', 'like', "%{$query}%")
                       ->orWhere('location', 'like', "%{$query}%")
                   ->orWhere('aircraft_registration', 'like', "%{$query}%")
                ->orWhere('location', 'like', "%{$query}%");
            });
        }

        // Get latest first
        $reports = $reports->latest()->get()->map(function ($report) {
            return [
                'id' => $report->id,
                'title' => trim($report->summary ?? $report->description),
                'type' => $report->report_type,
                'date' => date('F Y', strtotime($report->accident_date)),
                'location' => $report->location,
                'status' => $report->status,
                'summary' => trim($report->summary ?? $report->description),
                'file_url' => $report->follow_up_file ? asset('storage/' . $report->follow_up_file) : null,
            ];
        });

        return response()->json($reports);
    }

    public function investigationDetails(AccidentReport $result)
    {
        return Inertia::render('Site/InvestigationDetails', [
            'result' => $result
        ]);
    }


}
