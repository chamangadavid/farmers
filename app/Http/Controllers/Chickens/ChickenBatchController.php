<?php

namespace App\Http\Controllers\Chickens;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Chickens\ChickenBatch;
use App\Models\Chickens\ChickenExpense;
use Illuminate\Support\Facades\DB;

class ChickenBatchController extends Controller
{
    
    public function index(Request $request)
    {

        $chickens = ChickenBatch::with([
            'expenses',
             'sales.payments',
        ])

            ->when($request->search, function ($query) use ($request) {

                $query->where('batch_number', 'like', '%' . $request->search . '%')
                    ->orWhere('batch_name', 'like', '%' . $request->search . '%')
                    ->orWhere('breed', 'like', '%' . $request->search . '%');

            })

            ->latest()

            ->get()

            ->map(function ($batch) {

                $totalExpenses = $batch->expenses->sum('amount');
                $totalSales = $batch->sales->sum('total_amount');

                return [

                    'id' => $batch->id,
                    'arrival_date' => $batch->arrival_date,
                    'batch_number' => $batch->batch_number,
                    'batch_name' => $batch->batch_name,
                    'batch_size' => $batch->batch_size,
                    'mortality' => $batch->mortality,
                    'birds_sold' => $batch->birds_sold,
                    'birds_remaining' => $batch->birds_remaining,
                    'estimated_sale_date' => $batch->estimated_sale_date,
                    'breed' => $batch->breed,
                    'supplier' => $batch->supplier,
                    'purchase_price' => $batch->purchase_price,
                    'status' => $batch->status,
                    'total_expenses' => $totalExpenses,
                    'total_sales'=>$totalSales,
                    'profit_loss' => $totalSales - $totalExpenses,
                    'expenses' => $batch->expenses->map(function ($expense) {
                        return [
                            'id' => $expense->id,
                            'expense_date' => $expense->expense_date,
                            'item' => $expense->item,
                            'quantity' => $expense->quantity,
                            'unit_price' => $expense->unit_price,
                            'amount' => $expense->amount,
                        ];
                    }),

                ];

            });

        return response()->json([

            'chickens' => $chickens

        ]);
    }

    public function store(Request $request)
    {
        
        $request->validate([

            'arrival_date' => 'required|date',
            'batch_number' => 'required|string|max:100',
            'batch_size' => 'required|integer|min:1',
            'estimated_sale_date' => 'required|date',
            'mortality' => 'nullable|integer|min:0',
            'birds_sold' => 'nullable|integer|min:0',
            'birds_survived' => 'nullable|integer|min:0',
            'birds_remaining' => 'nullable|integer|min:0',
            'breed'=>'nullable',
            'supplier'=>'nullable',
            'purchase_price'=>'nullable',
            'status' => 'required|string',
            'notes' => 'nullable|string',
            'expenses' => 'nullable|array',
            'expenses.*.expense_date' => 'required|date',
            'expenses.*.item' => 'required|string',
            'expenses.*.quantity' => 'required|numeric|min:0',
            'expenses.*.unit_price' => 'required|numeric|min:0',
            'expenses.*.amount' => 'required|numeric|min:0',

        ]);

        DB::transaction(function () use ($request) {

            $chicken = ChickenBatch::create([

                'arrival_date' => $request->arrival_date,
                'batch_number' => $request->batch_number,
                'batch_name'=>$request->batch_name,
                'batch_size' => $request->batch_size,
                'estimated_sale_date' => $request->estimated_sale_date,
                'mortality' => $request->mortality,
                'birds_sold' => $request->birds_sold,
                'birds_survived' => $request->birds_survived,
                'birds_remaining' => $request->birds_remaining,
                'breed'=>$request->breed,
                'supplier'=>$request->supplier,
                'purchase_price'=>$request->purchase_price,
                'status' => $request->status,
                'notes' => $request->notes,

            ]);

            foreach ($request->expenses ?? [] as $expense) {

                $chicken->expenses()->create([
                    'expense_date' => $expense['expense_date'],
                    'item' => $expense['item'],
                    'quantity' => $expense['quantity'],
                    'unit_price' => $expense['unit_price'],
                    'amount' => $expense['amount'],

                ]);
            }
        });

        return response()->json([
            'message' => 'Chicken batch created successfully.'

        ]);

    }

    public function update(Request $request, ChickenBatch $chicken)
    {
        $request->validate([
            'arrival_date'=>'required|date',
            'batch_number'=>'required',
            'batch_name'=>'required',
            'batch_size'=>'required|integer',
            'estimated_sale_date'=>'required|date',
            'status'=>'required'
        ]);

        DB::transaction(function () use ($request,$chicken){

            $chicken->update([
                'arrival_date'=>$request->arrival_date,
                'estimated_sale_date'=>$request->estimated_sale_date,
                'batch_number'=>$request->batch_number,
                'batch_name'=>$request->batch_name,
                'batch_size'=>$request->batch_size,
                'mortality'=>$request->mortality,
                'birds_sold'=>$request->birds_sold,
                'birds_remaining'=>$request->birds_remaining,
                'breed'=>$request->breed,
                'supplier'=>$request->supplier,
                'purchase_price'=>$request->purchase_price,
                'status'=>$request->status,
                'notes'=>$request->notes,
            ]);

            // Remove old expenses
            $chicken->expenses()->delete();

            // Insert updated expenses
            foreach($request->expenses ?? [] as $expense){

                $chicken->expenses()->create([
                    'expense_date'=>$expense['expense_date'],
                    'item'=>$expense['item'],
                    'quantity'=>$expense['quantity'],
                    'unit_price'=>$expense['unit_price'],
                    'amount'=>$expense['amount']
                ]);

            }

        });

        return response()->json([
            'message'=>'Chicken batch updated successfully.'
        ]);
    }

}
