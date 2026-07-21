<?php

namespace App\Http\Controllers;

use App\Models\GeneralExpense;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\GeneralExpensesExport;
use Maatwebsite\Excel\Facades\Excel;

class GeneralExpenseController extends Controller
{

    public function index()
    {
        $expenses = GeneralExpense::latest('expense_date')->latest()->get();

        return response()->json([
            'data' => $expenses
        ]);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([

            'expense_date' => ['required', 'date'],
            'expense_category' => ['required', 'string', 'max:255'],
            'expense_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'payment_status' => ['required', 'string', 'max:255'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'vendor' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],

        ]);


        $expense = GeneralExpense::create($validated);

        return response()->json([

            'message' => 'General expense created successfully',
            'data' => $expense

        ], 201);
    }

    public function show(GeneralExpense $generalExpense)
    {
        return response()->json([

            'data' => $generalExpense

        ]);
    }

    public function update(Request $request, GeneralExpense $generalExpense) 
    {

        $validated = $request->validate([

            'expense_date' => ['required', 'date'],
            'expense_category' => ['required', 'string', 'max:255'],
            'expense_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'payment_status' => ['required', 'string', 'max:255'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'vendor' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],

        ]);


        $generalExpense->update($validated);

        return response()->json([

            'message' => 'General expense updated successfully',
            'data' => $generalExpense->fresh()

        ]);
    }

    public function destroy(GeneralExpense $generalExpense) 
    {

        $generalExpense->delete();

        return response()->json([

            'message' => 'General expense deleted successfully'

        ]);
    }

    public function exportPdf(Request $request)
    {

        $expenses = $this->filteredExpenses($request);
        $totalAmount = $expenses->sum('amount');
        $paidAmount = $expenses->where('payment_status', 'Paid')->sum('amount');
        $pendingAmount = $expenses->whereIn('payment_status',['Pending', 'Partial'])->sum('amount');
        $pdf = Pdf::loadView('general-expenses.pdf', [

                'expenses' => $expenses,
                'totalAmount' => $totalAmount,
                'paidAmount' => $paidAmount,
                'pendingAmount' => $pendingAmount,

            ]

        );

        return $pdf->download('general-expenses-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request) 
    {

        return Excel::download(new GeneralExpensesExport($request->all()),

            'general-expenses-' . now()->format('Y-m-d') . '.xlsx'

        );
    }


    private function filteredExpenses(Request $request)
    {

        $query = GeneralExpense::query();

        //search
        if ($request->filled('search')) {

            $search = $request->search;
            $query->where(function ($q) use ($search) {

                $q->where('expense_name', 'like', "%{$search}%")

                ->orWhere('expense_category', 'like', "%{$search}%")
                ->orWhere('vendor', 'like', "%{$search}%")
                ->orWhere('reference_number', 'like', "%{$search}%");

            });
        }


        //category
        if ($request->filled('category')) {

            $query->where('expense_category', $request->category);

        }

        //payment status
        if ($request->filled('status')) {

            $query->where('payment_status', $request->status);

        }

        //date filter
        switch ($request->date_filter) {


            case 'Today':
                $query->whereDate('expense_date', now()->toDateString());
                break;

            case 'Week':
                $query->whereBetween('expense_date', [now()->startOfWeek(), now()->endOfWeek()]);
                break;

            case 'Month':
                $query->whereBetween('expense_date', [now()->startOfMonth(), now()->endOfMonth()]);
                break;


            case 'Year':
                $query->whereBetween('expense_date', [now()->startOfYear(), now()->endOfYear()]);
                break;


            case 'Custom':
                if ($request->filled('date_from') && $request->filled('date_to')) {

                    $query->whereBetween('expense_date', [$request->date_from, $request->date_to]);
                }
                break;

        }

        return $query->latest('expense_date')->latest()->get();
    }


}
