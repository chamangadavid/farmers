<?php

namespace App\Http\Controllers\Chickens;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chickens\ChickenBatch;
use App\Models\Chickens\ChickenSale;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;

class ChickenSalesController extends Controller
{
    
    //list sales
    public function index()
    {
        return response()->json([
            'sales' => ChickenSale::with('batch')
                ->latest()
                ->get()
        ]);
    }

    //store sales
    public function store(Request $request)
    {
        $request->validate([

            'chicken_batch_id' => 'required|exists:chicken_batches,id',
            'sale_date' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'notes' => 'nullable|string'

        ]);

        DB::transaction(function () use ($request) {
            $batch = ChickenBatch::findOrFail($request->chicken_batch_id);

            //Validate remaining birds
            if ($request->quantity > $batch->birds_remaining) {
                abort(422, 'Quantity exceeds birds remaining.');

            }

            //create sale
            $sale = ChickenSale::create([

                'chicken_batch_id' => $batch->id,
                'sale_date' => $request->sale_date,
                'quantity' => $request->quantity,
                'unit_price' => $request->unit_price,
                'total_amount' => $request->quantity * $request->unit_price,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'notes' => $request->notes

            ]);


            //update batch
            $batch->birds_sold += $request->quantity;
            $batch->birds_remaining -= $request->quantity;


            //update status
            if ($batch->birds_remaining == 0) {
                $batch->status = 'Completed';

            } else {
                $batch->status = 'Selling';

            }

            $batch->save();

        });

        return response()->json([

            'message' => 'Sale recorded successfully.'

        ]);

    }

    //update sales
    public function update(Request $request, ChickenSale $sale)
    {

        return response()->json([
            'message'=>'Coming next'

        ]);

    }

    public function updateSales(Request $request, ChickenSale $sale)
    {

        $validated=$request->validate([

            'sale_date'=>'required|date',

            'quantity'=>'required|integer|min:1',

            'unit_price'=>'required|numeric|min:0',

            'customer_name'=>'nullable|string',

            'customer_phone'=>'nullable|string',

            'notes'=>'nullable|string'

        ]);



        DB::transaction(function() use($sale,$validated){


            $batch=$sale->batch;


            /*
            old quantity difference
            */

            $difference =
                $validated['quantity']
                -
                $sale->quantity;



            if($difference > 0){


                // selling more birds

                if($batch->birds_remaining < $difference){

                    throw new \Exception(
                        'Not enough birds available'
                    );

                }


                $batch->birds_remaining -= $difference;

                $batch->birds_sold += $difference;


            }


            elseif($difference < 0){


                // reducing sale quantity

                $returnBirds =
                    abs($difference);


                $batch->birds_remaining += $returnBirds;

                $batch->birds_sold -= $returnBirds;


            }



            $batch->save();



            $validated['total_amount'] =
                $validated['quantity']
                *
                $validated['unit_price'];



            $sale->update($validated);


        });



        return response()->json([

            'message'=>'Sale updated successfully'

        ]);

    }

    public function show(ChickenBatch $batch)
    {
        $batch->load([
            'expenses',
            'sales'
        ]);

        $totalExpenses = $batch->expenses->sum('amount');
        $totalSales    = $batch->sales->sum('total_amount');

        return response()->json([

            'batch' => [

                'id' => $batch->id,
                'batch_number' => $batch->batch_number,
                'batch_name' => $batch->batch_name,
                'arrival_date' => $batch->arrival_date,
                'batch_size' => $batch->batch_size,
                'estimated_sale_date' => $batch->estimated_sale_date,
                'breed' => $batch->breed,
                'supplier' => $batch->supplier,
                'status' => $batch->status,
                'mortality' => $batch->mortality,
                'birds_sold' => $batch->birds_sold,
                'birds_remaining' => $batch->birds_remaining,

            ],

            'sales' => $batch->sales,

            'summary' => [

                'batch_size' => $batch->batch_size,
                'mortality' => $batch->mortality,
                'birds_sold' => $batch->birds_sold,
                'birds_remaining' => $batch->birds_remaining,
                'total_expenses' => $totalExpenses,
                'total_sales' => $totalSales,
                'profit' => $totalSales - $totalExpenses,

            ]

        ]);
    }

    public function receipt(ChickenSale $sale)
    {
        // Load relationships
        $sale->load([
            'batch'
        ]);

        return view('receipts.chicken-sale', [

            'sale' => $sale

        ]);
    }

    public function downloadReceipt(ChickenSale $sale)
    {

        $sale->load([
            'batch'
        ]);


        $pdf =Pdf::loadView(
            'receipts.chicken-sale',
            [
                'sale' => $sale
            ]
        );


        return $pdf->download(
            'Chicken-Sale-Receipt-'.$sale->id.'.pdf'
        );

    }


    public function destroy(ChickenSale $sale)
    {

        DB::transaction(function () use ($sale) {
            
            $batch = $sale->batch;
            if (!$batch) {

                throw new \Exception('Chicken batch not found.');

            }


            /*
            |--------------------------------------------------------------------------
            | Return birds back to stock
            |--------------------------------------------------------------------------
            */

            $batch->birds_sold -= $sale->quantity;
            $batch->birds_remaining += $sale->quantity;

            /*
            |--------------------------------------------------------------------------
            | Update batch status
            |--------------------------------------------------------------------------
            */


            if ($batch->birds_sold <= 0) {

                $batch->birds_sold = 0;
                $batch->status = 'Growing';

            }

            elseif ($batch->birds_remaining > 0) {
                $batch->status = 'Selling';

            }

            $batch->save();

            /*
            |--------------------------------------------------------------------------
            | Delete sale
            |--------------------------------------------------------------------------
            */

            $sale->delete();

        });


        return response()->json([

            'message'=>'Sale reversed successfully.'

        ]);

    }


}
