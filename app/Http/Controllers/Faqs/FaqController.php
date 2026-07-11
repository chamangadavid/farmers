<?php

namespace App\Http\Controllers\Faqs;

use App\Http\Controllers\Controller;
use App\Models\Faqs\Faq;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FaqController extends Controller
{

    public function JobVacancies()
    {
        return Inertia::render('MyAAIB/Faqs/Index');
    }

    public function index(Request $request)
    {
        $search = $request->query('search', []);

        $faqs = Faq::query()
            ->when(!empty($search['query']), function($query) use ($search) {
                $query->where('question', 'like', "%{$search['query']}%")
                    ->orWhere('answer', 'like', "%{$search['query']}%");
            })
            ->when(!empty($search['category']), function($query) use ($search) {
                $query->where('category', $search['category']);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['faqs' => $faqs]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'category' => [
                'required',
                Rule::in([
                    'General',
                    'Vegetables',
                    'Fresh Fruits',
                    'Poultry',
                    'Farm Services',
                    'Orders & Delivery',
                    'Payments'
                ])
            ],
            'question' => 'required|string',
            'answer' => 'required|string'
        ]);

        // $validator = Validator::make($request->all(), [
        //     'category' => 'required|string|max:255',
        //     'question' => 'required|string',
        //     'answer'   => 'required|string',
        // ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $faq = Faq::create($request->all());

        return response()->json(['faq' => $faq, 'message' => 'FAQ created successfully']);
    }

    // Show single FAQ
    public function show(Faq $faq)
    {
        return response()->json(['faq' => $faq]);
    }

    // Update FAQ
    public function update(Request $request, Faq $faq)
    {
        $validator = Validator::make($request->all(), [
            'category' => [
                'required',
                Rule::in([
                    'General',
                    'Vegetables',
                    'Fresh Fruits',
                    'Poultry',
                    'Farm Services',
                    'Orders & Delivery',
                    'Payments'
                ])
            ],
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $faq->update([
            'category' => $request->category,
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        return response()->json([
            'faq' => $faq,
            'message' => 'FAQ updated successfully'
        ]);
    }

    // public function update(Request $request, Faq $faq)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'category' => 'required|string|max:255',
    //         'question' => 'required|string',
    //         'answer'   => 'required|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $faq->update($request->all());

    //     return response()->json(['faq' => $faq, 'message' => 'FAQ updated successfully']);
    // }

    // Delete FAQ
    public function destroy(Faq $faq)
    {
        $faq->delete();
        return response()->json(['message' => 'FAQ deleted successfully']);
    }

    // Frontend FAQ retrieval with optional category and search filters
    public function getfrontIndex(Request $request)
{
    $category = $request->category;
    $search = $request->search;

    $faqs = Faq::query()

        ->when($category && $category != 'all', function ($q) use ($category) {
            $q->where('category', $category);
        })

        ->when($search, function ($q) use ($search) {
            $q->where('question', 'like', "%{$search}%")
              ->orWhere('answer', 'like', "%{$search}%");
        })

        ->orderBy('created_at')

        ->get();

    return response()->json($faqs);
}




}
