<?php

namespace App\Http\Controllers\Vegetables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vegetables\VegetableExpense;
use App\Models\Vegetables\VegetableProduction;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class VegetableExpenseController extends Controller
{
    
 /*
    |--------------------------------------------------------------------------
    | List Expenses
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $expenses = VegetableExpense::with([
            'production.vegetableType'
        ])
        ->latest()
        ->get();

        return response()->json($expenses);
    }

    /*
    |--------------------------------------------------------------------------
    | Production Batches
    |--------------------------------------------------------------------------
    */

    public function productions()
    {
        return VegetableProduction::with('vegetableType')
            ->orderBy('batch_number')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Store Expense
    |--------------------------------------------------------------------------
    */
public function store(Request $request)
{
    $validated = $request->validate([

        'vegetable_production_id' => 'required|exists:vegetable_productions,id',

        'expense_date' => 'required|date',

        'expense_category' => 'required|string|max:255',

        'description' => 'nullable|string',

        'supplier' => 'nullable|string|max:255',

        'receipt_number' => 'nullable|string|max:100',

        'quantity' => 'required|numeric|min:0',

        'unit' => 'nullable|string|max:50',

        'unit_cost' => 'required|numeric|min:0',

        'payment_method' => 'required|string|max:100',

        'status' => 'required|string|max:100',

        'notes' => 'nullable|string',

    ]);

    // Calculate amount on the server
    $validated['amount'] =
        $validated['quantity'] * $validated['unit_cost'];

    DB::transaction(function () use ($validated) {

        VegetableExpense::create($validated);

    });

    return response()->json([

        'message' => 'Expense created successfully.'

    ]);
}

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([

    //         'vegetable_production_id' => 'required|exists:vegetable_productions,id',

    //         'expense_date' => 'required|date',

    //         'expense_category' => 'required|string|max:255',

    //         'description' => 'nullable|string',

    //         'quantity' => 'required|numeric|min:0',

    //         'unit_cost' => 'required|numeric|min:0',

    //         'total_cost' => 'required|numeric|min:0',

    //         'supplier' => 'nullable|string|max:255',

    //         'payment_method' => 'required|string|max:100',

    //         'reference_number' => 'nullable|string|max:100',

    //         'notes' => 'nullable|string'

    //     ]);

    //     DB::transaction(function () use ($validated) {

    //         VegetableExpense::create($validated);

    //     });

    //     return response()->json([

    //         'message' => 'Expense created successfully.'

    //     ]);
    // }

    /*
    |--------------------------------------------------------------------------
    | Show Expense
    |--------------------------------------------------------------------------
    */

    public function show(VegetableExpense $expense)
    {
        $expense->load([
            'production.vegetableType'
        ]);

        return response()->json($expense);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Expense
    |--------------------------------------------------------------------------
    */
public function update(Request $request, VegetableExpense $expense)
{
    $validated = $request->validate([

        'vegetable_production_id' => 'required|exists:vegetable_productions,id',

        'expense_date' => 'required|date',

        'expense_category' => 'required|string|max:255',

        'description' => 'nullable|string',

        'supplier' => 'nullable|string|max:255',

        'receipt_number' => 'nullable|string|max:100',

        'quantity' => 'required|numeric|min:0',

        'unit' => 'nullable|string|max:50',

        'unit_cost' => 'required|numeric|min:0',

        'payment_method' => 'required|string|max:100',

        'status' => 'nullable|string|max:100',

        'notes' => 'nullable|string',

    ]);

    //$validated['status'] = $validated['status'] ?? 'Paid';

    // Recalculate amount
    $validated['amount'] =
        $validated['quantity'] * $validated['unit_cost'];

    DB::transaction(function () use ($expense, $validated) {

        $expense->update($validated);

    });

    return response()->json([

        'message' => 'Expense updated successfully.'

    ]);
}
    // public function update(Request $request, VegetableExpense $expense)
    // {
    //     $validated = $request->validate([

    //         'vegetable_production_id' => 'required|exists:vegetable_productions,id',

    //         'expense_date' => 'required|date',

    //         'expense_category' => 'required|string|max:255',

    //         'description' => 'nullable|string',

    //         'quantity' => 'required|numeric|min:0',

    //         'unit_cost' => 'required|numeric|min:0',

    //         'total_cost' => 'required|numeric|min:0',

    //         'supplier' => 'nullable|string|max:255',

    //         'payment_method' => 'required|string|max:100',

    //         'reference_number' => 'nullable|string|max:100',

    //         'notes' => 'nullable|string'

    //     ]);

    //     DB::transaction(function () use ($expense, $validated) {

    //         $expense->update($validated);

    //     });

    //     return response()->json([

    //         'message' => 'Expense updated successfully.'

    //     ]);
    // }

    /*
    |--------------------------------------------------------------------------
    | Delete Expense
    |--------------------------------------------------------------------------
    */

    public function destroy(VegetableExpense $expense)
    {
        $expense->delete();

        return response()->json([

            'message' => 'Expense deleted successfully.'

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Export PDF
    |--------------------------------------------------------------------------
    */

    public function exportPdf()
    {
        $expenses = VegetableExpense::with([
            'production.vegetableType'
        ])
        ->orderBy('expense_date')
        ->get();

        $pdf = Pdf::loadView(
            'pdf.vegetable-expenses',
            compact('expenses')
        );

        return $pdf->download(
            'Vegetable_Expenses_Report.pdf'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Export Excel
    |--------------------------------------------------------------------------
    */

    // public function exportExcel()
    // {
    //     return Excel::download(
    //         new VegetableExpenseExport(),
    //         'Vegetable_Expenses.xlsx'
    //     );
    // }
}
