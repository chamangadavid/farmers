<?php

namespace App\Http\Controllers\Vegetables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Vegetables\VegetableHarvest;
use App\Models\Vegetables\VegetableSale;
use Barryvdh\DomPDF\Facade\Pdf;

class VegetableSaleController extends Controller
{
    
 /*
    |--------------------------------------------------------------------------
    | List Sales
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $sales = VegetableSale::with([

            'harvest.production.vegetableType'

        ])
        ->latest()
        ->get();

        return response()->json($sales);
    }

    /*
    |--------------------------------------------------------------------------
    | Harvests Available For Sales
    |--------------------------------------------------------------------------
    */

    public function harvests()
    {
        $harvests = VegetableHarvest::with([

            'production.vegetableType',
            'sales'

        ])
        ->get()
        ->map(function ($harvest) {

            $soldQuantity = $harvest->sales->sum('quantity');

            $harvest->available_quantity =
                $harvest->quantity - $soldQuantity;

            return $harvest;
        })
        ->filter(function ($harvest) {

            return $harvest->available_quantity > 0;

        })
        ->values();

        return response()->json($harvests);
    }

    /*
    |--------------------------------------------------------------------------
    | View Single Sale
    |--------------------------------------------------------------------------
    */

    public function show(VegetableSale $sale)
    {
        $sale->load([

            'harvest.production.vegetableType'

        ]);

        return response()->json($sale);
    }

    /*
    |--------------------------------------------------------------------------
    | Store Sale
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'vegetable_harvest_id' => 'required|exists:vegetable_harvests,id',

            'sale_date' => 'required|date',

            'customer_name' => 'required|string|max:255',

            'customer_phone' => 'nullable|string|max:100',

            'customer_email' => 'nullable|email|max:255',

            'quantity' => 'required|numeric|min:0.01',

            'unit_price' => 'required|numeric|min:0',

            'total_amount' => 'required|numeric|min:0',

            'payment_method' => 'required|string',

            'payment_status' => 'required|string',

            'invoice_number' => 'nullable|string|max:100',

            'receipt_number' => 'nullable|string|max:100',

            'notes' => 'nullable|string'

        ]);

        DB::transaction(function () use ($validated) {

            $harvest = VegetableHarvest::with('sales')
                ->findOrFail($validated['vegetable_harvest_id']);

            $sold = $harvest->sales->sum('quantity');

            $available = $harvest->quantity - $sold;

            if ($validated['quantity'] > $available) {

                abort(
                    422,
                    "Only {$available} Kg is available for sale."
                );
            }

            VegetableSale::create($validated);
        });

        return response()->json([

            'message' => 'Sale recorded successfully.'

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Sale
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, VegetableSale $sale)
    {
        $validated = $request->validate([

            'vegetable_harvest_id' => 'required|exists:vegetable_harvests,id',

            'sale_date' => 'required|date',

            'customer_name' => 'required|string|max:255',

            'customer_phone' => 'nullable|string|max:100',

            'customer_email' => 'nullable|email|max:255',

            'quantity' => 'required|numeric|min:0.01',

            'unit_price' => 'required|numeric|min:0',

            'total_amount' => 'required|numeric|min:0',

            'payment_method' => 'required|string',

            'payment_status' => 'required|string',

            'invoice_number' => 'nullable|string|max:100',

            'receipt_number' => 'nullable|string|max:100',

            'notes' => 'nullable|string'

        ]);

        DB::transaction(function () use ($sale, $validated) {

            $harvest = VegetableHarvest::with('sales')
                ->findOrFail($validated['vegetable_harvest_id']);

            $sold = $harvest->sales
                ->where('id', '!=', $sale->id)
                ->sum('quantity');

            $available = $harvest->quantity - $sold;

            if ($validated['quantity'] > $available) {

                abort(
                    422,
                    "Only {$available} Kg is available."
                );
            }

            $sale->update($validated);
        });

        return response()->json([

            'message' => 'Sale updated successfully.'

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Sale
    |--------------------------------------------------------------------------
    */

    public function destroy(VegetableSale $sale)
    {
        $sale->delete();

        return response()->json([

            'message' => 'Sale deleted successfully.'

        ]);
    }

    public function downloadPdf(VegetableSale $sale)
    {

        $sale->load([

            'harvest.production.vegetableType'

        ]);


        $pdf = Pdf::loadView(

            'pdf.vegetable_sale',

            [

                'sale'=>$sale

            ]

        );


        return $pdf->download(

            'Vegetable-Sale-'.$sale->invoice_number.'.pdf'

        );

    }

    public function exportPdf()
{

    $sales = VegetableSale::with([

        'harvest.production.vegetableType'

    ])

    ->latest()

    ->get();



    $pdf = Pdf::loadView(

        'pdf.vegetable_sales_report',

        [

            'sales'=>$sales

        ]

    )

    ->setPaper(

        'a4',

        'landscape'

    );



    return $pdf->download(

        'Vegetable-Sales-Report.pdf'

    );

}



}
