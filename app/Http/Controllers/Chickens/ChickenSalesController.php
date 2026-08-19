<?php

namespace App\Http\Controllers\Chickens;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chickens\ChickenBatch;
use App\Models\Chickens\ChickenSale;
use App\Models\Chickens\ChickenSalePayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;


class ChickenSalesController extends Controller
{
    
    //list sales
    public function index()
    {
        return response()->json([
            'sales' => ChickenSale::with([
                'batch',
                'payments',
            ])
                ->latest()
                ->get()
        ]);
    }

    //store sales
//   public function store(Request $request)
//     {
//         $request->validate([

//             'chicken_batch_id' => 'required|exists:chicken_batches,id',
//             'sale_date' => 'required|date',
//             'quantity' => 'required|integer|min:1',
//             'unit_price' => 'required|numeric|min:0',
//             'payment_method' => 'required|in:Cash,Credit,Card,Cheque,Mobile Money,Bank Transfer',
//             'initial_payment' => 'nullable|numeric|min:0',
//             'customer_name' => 'nullable|string|max:255',
//             'customer_phone' => 'nullable|string|max:50',
//             'notes' => 'nullable|string',
            

//         ]);

//         DB::transaction(function () use ($request) {

//             $batch = ChickenBatch::findOrFail(
//                 $request->chicken_batch_id
//             );

//             if ($request->quantity > $batch->birds_remaining) {

//                 abort(
//                     422,
//                     'Quantity exceeds birds remaining.'
//                 );

//             }

//             $totalAmount =
//                 $request->quantity *
//                 $request->unit_price;


//             $sale = ChickenSale::create([

//                 'chicken_batch_id' => $batch->id,

//                 'sale_date' => $request->sale_date,

//                 'quantity' => $request->quantity,

//                 'unit_price' => $request->unit_price,

//                 'total_amount' => $totalAmount,

//                 'customer_name' => $request->customer_name,

//                 'customer_phone' => $request->customer_phone,

//                 'notes' => $request->notes

//             ]);


//             /*
//             |--------------------------------------------------------------------------
//             | Create Initial Payment
//             |--------------------------------------------------------------------------
//             */

//             $paymentAmount = 0;


//             if ($request->payment_method === 'Credit') {

//                 // Credit payment is optional
//                 $paymentAmount =
//                     $request->initial_payment ?? 0;

//             } else {

//                 // Cash, Card, Cheque, etc.
//                 // are considered fully paid
//                 $paymentAmount =
//                     $totalAmount;

//             }


//             if ($paymentAmount > 0) {

//                 ChickenSalePayment::create([

//                     'chicken_sale_id' => $sale->id,

//                     'payment_date' => $request->sale_date,

//                     'amount' => $paymentAmount,

//                     'payment_method' =>
//                         $request->payment_method,

//                     'notes' => 'Initial payment'

//                 ]);

//             }


//             // Update batch
//             $batch->birds_sold += $request->quantity;

//             $batch->birds_remaining -= $request->quantity;


//             if ($batch->birds_remaining == 0) {

//                 $batch->status = 'Completed';

//             } else {

//                 $batch->status = 'Selling';

//             }


//             $batch->save();

//         });

//         // DB::transaction(function () use ($request) {
//         //     $batch = ChickenBatch::findOrFail($request->chicken_batch_id);

//         //     //Validate remaining birds
//         //     if ($request->quantity > $batch->birds_remaining) {
//         //         abort(422, 'Quantity exceeds birds remaining.');

//         //     }

//         //     //create sale
//         //     $sale = ChickenSale::create([

//         //         'chicken_batch_id' => $batch->id,
//         //         'sale_date' => $request->sale_date,
//         //         'quantity' => $request->quantity,
//         //         'unit_price' => $request->unit_price,
//         //         'total_amount' => $request->quantity * $request->unit_price,
//         //         'customer_name' => $request->customer_name,
//         //         'customer_phone' => $request->customer_phone,
//         //         'notes' => $request->notes

//         //     ]);


//         //     //update batch
//         //     $batch->birds_sold += $request->quantity;
//         //     $batch->birds_remaining -= $request->quantity;


//         //     //update status
//         //     if ($batch->birds_remaining == 0) {
//         //         $batch->status = 'Completed';

//         //     } else {
//         //         $batch->status = 'Selling';

//         //     }

//         //     $batch->save();

//         // });

//         return response()->json([

//             'message' => 'Sale recorded successfully.'

//         ]);

//     }


public function store(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | Validate Request
    |--------------------------------------------------------------------------
    */

    $request->validate([

        'chicken_batch_id' => [
            'required',
            'exists:chicken_batches,id'
        ],

        'sale_date' => [
            'required',
            'date'
        ],

        'sale_type' => [
            'required',
            'in:Per Bird,Per Kg'
        ],

        'quantity' => [
            'required',
            'integer',
            'min:1'
        ],

        'unit_price' => [
            'nullable',
            'numeric',
            'min:0'
        ],

        'total_weight' => [
            'nullable',
            'numeric',
            'min:0.01'
        ],

        'price_per_kg' => [
            'nullable',
            'numeric',
            'min:0'
        ],

        'payment_method' => [
            'required',
            'in:Cash,Credit,Card,Cheque,Mobile Money,Bank Transfer'
        ],

        'initial_payment' => [
            'nullable',
            'numeric',
            'min:0'
        ],

        'customer_name' => [
            'nullable',
            'string',
            'max:255'
        ],

        'customer_phone' => [
            'nullable',
            'string',
            'max:50'
        ],

        'notes' => [
            'nullable',
            'string'
        ]

    ]);


    /*
    |--------------------------------------------------------------------------
    | Validate Sale Type-Specific Fields
    |--------------------------------------------------------------------------
    */

    if ($request->sale_type === 'Per Bird') {

        if (
            !$request->unit_price ||
            $request->unit_price <= 0
        ) {

            return response()->json([

                'message' =>
                    'Price per bird is required.'

            ], 422);

        }

    }


    if ($request->sale_type === 'Per Kg') {

        if (
            !$request->total_weight ||
            $request->total_weight <= 0
        ) {

            return response()->json([

                'message' =>
                    'Total weight is required.'

            ], 422);

        }


        if (
            !$request->price_per_kg ||
            $request->price_per_kg <= 0
        ) {

            return response()->json([

                'message' =>
                    'Price per Kg is required.'

            ], 422);

        }

    }
    


    /*
    |--------------------------------------------------------------------------
    | Process Sale
    |--------------------------------------------------------------------------
    */

    DB::transaction(function () use ($request) {


        /*
        |--------------------------------------------------------------------------
        | Get Batch
        |--------------------------------------------------------------------------
        */

        $batch = ChickenBatch::findOrFail(

            $request->chicken_batch_id

        );


        /*
        |--------------------------------------------------------------------------
        | Validate Available Birds
        |--------------------------------------------------------------------------
        */

        if (

            $request->quantity >

            $batch->birds_remaining

        ) {

            abort(

                422,

                'Quantity exceeds birds remaining.'

            );

        }


        /*
        |--------------------------------------------------------------------------
        | Calculate Total Amount
        |--------------------------------------------------------------------------
        */

        if (

            $request->sale_type === 'Per Kg'

        ) {


            $totalAmount =

                $request->total_weight *

                $request->price_per_kg;

        $unitPrice =
        $request->price_per_kg;


        } else {


            $totalAmount =

                $request->quantity *

                $request->unit_price;

            $unitPrice =
                $request->unit_price;

        }


        /*
        |--------------------------------------------------------------------------
        | Create Sale
        |--------------------------------------------------------------------------
        */

        $sale = ChickenSale::create([

            'chicken_batch_id' =>

                $batch->id,

            'sale_date' =>

                $request->sale_date,

            'sale_type' =>

                $request->sale_type,

            'quantity' =>

                $request->quantity,

            'total_weight' =>

                $request->sale_type === 'Per Kg'

                    ? $request->total_weight

                    : null,

            'price_per_kg' =>

                $request->sale_type === 'Per Kg'

                    ? $request->price_per_kg

                    : null,

                    'unit_price' => $unitPrice,

            'total_amount' =>

                $totalAmount,

            'customer_name' =>

                $request->customer_name,

            'customer_phone' =>

                $request->customer_phone,

            'notes' =>

                $request->notes

        ]);


        /*
        |--------------------------------------------------------------------------
        | Determine Initial Payment
        |--------------------------------------------------------------------------
        */

        $paymentAmount = 0;


        if (

            $request->payment_method === 'Credit'

        ) {


            // Credit sales can have an optional
            // initial payment

            $paymentAmount =

                $request->initial_payment ?? 0;


        } else {


            // Other payment methods are treated
            // as fully paid

            $paymentAmount =

                $totalAmount;

        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Overpayment
        |--------------------------------------------------------------------------
        */

        if (

            $paymentAmount >

            $totalAmount

        ) {

            abort(

                422,

                'Initial payment cannot exceed total sale amount.'

            );

        }


        /*
        |--------------------------------------------------------------------------
        | Create Initial Payment
        |--------------------------------------------------------------------------
        */

        if (

            $paymentAmount > 0

        ) {


            ChickenSalePayment::create([

                'chicken_sale_id' =>

                    $sale->id,

                'payment_date' =>

                    $request->sale_date,

                'amount' =>

                    $paymentAmount,

                'payment_method' =>

                    $request->payment_method,

                'notes' =>

                    'Initial payment'

            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Update Batch Inventory
        |--------------------------------------------------------------------------
        */

        $batch->birds_sold +=

            $request->quantity;


        $batch->birds_remaining -=

            $request->quantity;


        /*
        |--------------------------------------------------------------------------
        | Update Batch Status
        |--------------------------------------------------------------------------
        */

        if (

            $batch->birds_remaining <= 0

        ) {

            $batch->birds_remaining = 0;

            $batch->status =

                'Completed';


        } else {


            $batch->status =

                'Selling';

        }


        $batch->save();

    });


    /*
    |--------------------------------------------------------------------------
    | Return Response
    |--------------------------------------------------------------------------
    */

    return response()->json([

        'message' =>

            'Sale recorded successfully.'

    ]);

}

    // public function store(Request $request)
    // {
    //     $request->validate([

    //         'chicken_batch_id' => 'required|exists:chicken_batches,id',
    //         'sale_date' => 'required|date',
    //         'quantity' => 'required|integer|min:1',
    //         'unit_price' => 'required|numeric|min:0',
    //         'payment_method' => 'required|in:Cash,Credit,Card,Cheque,Mobile Money,Bank Transfer',
    //         'initial_payment' => 'nullable|numeric|min:0',
    //         'customer_name' => 'nullable|string|max:255',
    //         'customer_phone' => 'nullable|string|max:50',
    //         'notes' => 'nullable|string'

    //     ]);

    //     DB::transaction(function () use ($request) {

    //         $batch = ChickenBatch::findOrFail(
    //             $request->chicken_batch_id
    //         );

    //         if ($request->quantity > $batch->birds_remaining) {

    //             abort(
    //                 422,
    //                 'Quantity exceeds birds remaining.'
    //             );

    //         }

    //         $totalAmount =
    //             $request->quantity *
    //             $request->unit_price;


    //         $sale = ChickenSale::create([

    //             'chicken_batch_id' => $batch->id,

    //             'sale_date' => $request->sale_date,

    //             'quantity' => $request->quantity,

    //             'unit_price' => $request->unit_price,

    //             'total_amount' => $totalAmount,

    //             'customer_name' => $request->customer_name,

    //             'customer_phone' => $request->customer_phone,

    //             'notes' => $request->notes

    //         ]);


    //         /*
    //         |--------------------------------------------------------------------------
    //         | Create Initial Payment
    //         |--------------------------------------------------------------------------
    //         */

    //         $paymentAmount = 0;


    //         if ($request->payment_method === 'Credit') {

    //             // Credit payment is optional
    //             $paymentAmount =
    //                 $request->initial_payment ?? 0;

    //         } else {

    //             // Cash, Card, Cheque, etc.
    //             // are considered fully paid
    //             $paymentAmount =
    //                 $totalAmount;

    //         }


    //         if ($paymentAmount > 0) {

    //             ChickenSalePayment::create([

    //                 'chicken_sale_id' => $sale->id,

    //                 'payment_date' => $request->sale_date,

    //                 'amount' => $paymentAmount,

    //                 'payment_method' =>
    //                     $request->payment_method,

    //                 'notes' => 'Initial payment'

    //             ]);

    //         }


    //         // Update batch
    //         $batch->birds_sold += $request->quantity;

    //         $batch->birds_remaining -= $request->quantity;


    //         if ($batch->birds_remaining == 0) {

    //             $batch->status = 'Completed';

    //         } else {

    //             $batch->status = 'Selling';

    //         }


    //         $batch->save();

    //     });

    //     // DB::transaction(function () use ($request) {
    //     //     $batch = ChickenBatch::findOrFail($request->chicken_batch_id);

    //     //     //Validate remaining birds
    //     //     if ($request->quantity > $batch->birds_remaining) {
    //     //         abort(422, 'Quantity exceeds birds remaining.');

    //     //     }

    //     //     //create sale
    //     //     $sale = ChickenSale::create([

    //     //         'chicken_batch_id' => $batch->id,
    //     //         'sale_date' => $request->sale_date,
    //     //         'quantity' => $request->quantity,
    //     //         'unit_price' => $request->unit_price,
    //     //         'total_amount' => $request->quantity * $request->unit_price,
    //     //         'customer_name' => $request->customer_name,
    //     //         'customer_phone' => $request->customer_phone,
    //     //         'notes' => $request->notes

    //     //     ]);


    //     //     //update batch
    //     //     $batch->birds_sold += $request->quantity;
    //     //     $batch->birds_remaining -= $request->quantity;


    //     //     //update status
    //     //     if ($batch->birds_remaining == 0) {
    //     //         $batch->status = 'Completed';

    //     //     } else {
    //     //         $batch->status = 'Selling';

    //     //     }

    //     //     $batch->save();

    //     // });

    //     return response()->json([

    //         'message' => 'Sale recorded successfully.'

    //     ]);

    // }



    //update sales
    public function update(Request $request, ChickenSale $sale)
    {

        return response()->json([
            'message'=>'Coming next'

        ]);

    }
     

    public function updateSales(Request $request, ChickenSale $sale)
    {
        $validated = $request->validate([

            'sale_date' => 'required|date',
            'sale_type' => ['required', 'in:Per Bird,Per Kg'],
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'total_weight' => 'nullable|numeric|min:0.01',
            'price_per_kg' => 'nullable|numeric|min:0',
            'customer_name' => 'nullable|string',
            'customer_phone' => 'nullable|string',
            'notes' => 'nullable|string', 
            // 'payment_method' =>'nullable|in:Cash,Credit,Card,Cheque,Mobile Money,Bank Transfer',
            // 'payment_amount' =>'nullable|numeric',

            'payment_method' => [
                'nullable',
                'required_with:payment_amount',
                'in:Cash,Credit,Card,Cheque,Mobile Money,Bank Transfer',
            ], //No payment amount → payment method can be empty || Payment amount entered → payment method must be supplied.

            'payment_amount' => [
                'nullable',
                'numeric',
            ],
            

        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Sale Type Specific Fields
        |--------------------------------------------------------------------------
        */

        if ($validated['sale_type'] === 'Per Bird') {

            if (
                empty($validated['unit_price']) ||
                $validated['unit_price'] <= 0
            ) {

                throw new \Exception(
                    'Price per bird is required.'
                );
            }

        }


        if ($validated['sale_type'] === 'Per Kg') {

            if (
                empty($validated['total_weight']) ||
                $validated['total_weight'] <= 0
            ) {

                throw new \Exception(
                    'Total weight is required.'
                );
            }


            if (
                empty($validated['price_per_kg']) ||
                $validated['price_per_kg'] <= 0
            ) {

                throw new \Exception(
                    'Price per Kg is required.'
                );
            }

        }


        DB::transaction(function () use (
            $sale,
            $validated
        ) {

            $batch = $sale->batch;


            /*
            |--------------------------------------------------------------------------
            | Calculate Quantity Difference
            |--------------------------------------------------------------------------
            */

            $difference =
                $validated['quantity']
                -
                $sale->quantity;


            /*
            |--------------------------------------------------------------------------
            | Increase Quantity Sold
            |--------------------------------------------------------------------------
            */

            if ($difference > 0) {

                if (
                    $batch->birds_remaining < $difference
                ) {

                    throw new \Exception(
                        'Not enough birds available.'
                    );

                }

                $batch->birds_remaining -= $difference;

                $batch->birds_sold += $difference;
            }


            /*
            |--------------------------------------------------------------------------
            | Reduce Quantity Sold
            |--------------------------------------------------------------------------
            */

            elseif ($difference < 0) {

                $returnBirds = abs($difference);

                $batch->birds_remaining += $returnBirds;

                $batch->birds_sold -= $returnBirds;
            }


            /*
            |--------------------------------------------------------------------------
            | Update Batch Status
            |--------------------------------------------------------------------------
            */

            if ($batch->birds_remaining <= 0) {

                $batch->birds_remaining = 0;

                $batch->status = 'Completed';

            } else {

                $batch->status = 'Selling';
            }


            $batch->save();


            /*
            |--------------------------------------------------------------------------
            | Calculate New Sale Amount
            |--------------------------------------------------------------------------
            */

            if ($validated['sale_type'] === 'Per Kg') {

                $newTotalAmount =
                    $validated['total_weight']
                    *
                    $validated['price_per_kg'];

                $unitPrice =
                    $validated['price_per_kg'];

            } else {

                $newTotalAmount =
                    $validated['quantity']
                    *
                    $validated['unit_price'];

                $unitPrice =
                    $validated['unit_price'];
            }


            /*
            |--------------------------------------------------------------------------
            | Payment Information
            |--------------------------------------------------------------------------
            */

            $paymentAmount =
                $validated['payment_amount'] ?? null;

            $paymentMethod =
                $validated['payment_method'] ?? null;

                Log::info('CHICKEN SALE EDIT VALUES', [
                'sale_id' => $sale->id,
                'sale_type' => $validated['sale_type'] ?? null,
                'quantity' => $validated['quantity'] ?? null,
                'unit_price' => $validated['unit_price'] ?? null,
                'total_weight' => $validated['total_weight'] ?? null,
                'price_per_kg' => $validated['price_per_kg'] ?? null,
                'payment_amount' => $paymentAmount,
                'payment_method' => $paymentMethod,
            ]);


            unset(
                $validated['payment_amount'],
                $validated['payment_method']
            );


            /*
            |--------------------------------------------------------------------------
            | Update Sale
            |--------------------------------------------------------------------------
            */

            $validated['unit_price'] =
                $unitPrice;

            $validated['total_amount'] =
                $newTotalAmount;


            /*
            | Clear fields that don't apply
            |--------------------------------------------------------------------------
            */

            if ($validated['sale_type'] === 'Per Bird') {

                $validated['total_weight'] = null;

                $validated['price_per_kg'] = null;

            } else {

                $validated['unit_price'] = $unitPrice;
            }


            $sale->update($validated);


            /*
            |--------------------------------------------------------------------------
            | Record Payment / Payment Adjustment
            |--------------------------------------------------------------------------
            */

            if (
                $paymentAmount !== null &&
                $paymentAmount != 0
            ) {

                $currentAmountPaid =
                    $sale->payments()->sum('amount');


                $newAmountPaid =
                    $currentAmountPaid +
                    $paymentAmount;


                /*
                |--------------------------------------------------------------------------
                | Prevent Negative Amount Paid
                |--------------------------------------------------------------------------
                */

                if ($newAmountPaid < 0) {

                    throw new \Exception(
                        'Payment adjustment cannot make the total amount paid negative.'
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | Prevent Overpayment
                |--------------------------------------------------------------------------
                */

                if ($newAmountPaid > $newTotalAmount) {
                    Log::info('CHICKEN SALE PAYMENT CHECK', [
    'sale_id' => $sale->id,
    'current_amount_paid' => $currentAmountPaid,
    'payment_amount' => $paymentAmount,
    'new_amount_paid' => $newAmountPaid,
    'new_total_amount' => $newTotalAmount,
]);

                    throw new \Exception(
                        'Payment cannot exceed the total sale amount.'
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | Create Payment / Adjustment
                |--------------------------------------------------------------------------
                */

                if (
                        $paymentAmount !== null &&
                        $paymentAmount != 0
                    ) {

                        if (empty($paymentMethod)) {
                            throw new \Exception(
                                'Payment method is required when entering a payment amount.'
                            );
                        }

                        $currentAmountPaid =
                            $sale->payments()->sum('amount');

                        $newAmountPaid =
                            $currentAmountPaid + $paymentAmount;

                        if ($newAmountPaid < 0) {
                            throw new \Exception(
                                'Payment adjustment cannot make the total amount paid negative.'
                            );
                        }

                        if ($newAmountPaid > $newTotalAmount) {
                            Log::info('CHICKEN SALE PAYMENT CHECK', [
    'sale_id' => $sale->id,
    'current_amount_paid' => $currentAmountPaid,
    'payment_amount' => $paymentAmount,
    'new_amount_paid' => $newAmountPaid,
    'new_total_amount' => $newTotalAmount,
]);
                            throw new \Exception(
                                'Payment cannot exceed the total sale amount.'
                            );
                        }

                        ChickenSalePayment::create([

                            'chicken_sale_id' =>
                                $sale->id,

                            'payment_date' =>
                                now()->toDateString(),

                            'amount' =>
                                $paymentAmount,

                            'payment_method' =>
                                $paymentMethod,

                            'notes' =>
                                $paymentAmount < 0
                                    ? 'Payment adjustment during sale update'
                                    : 'Payment added during sale update',

                        ]);
                    }

                // ChickenSalePayment::create([

                //     'chicken_sale_id' =>
                //         $sale->id,

                //     'payment_date' =>
                //         now()->toDateString(),

                //     'amount' =>
                //         $paymentAmount,

                //     'payment_method' =>
                //         $paymentMethod,

                //     'notes' =>
                //         $paymentAmount < 0
                //             ? 'Payment adjustment during sale update'
                //             : 'Payment added during sale update',

                // ]);
            }

        });


        return response()->json([

            'message' =>
                'Sale updated successfully'

        ]);
    }

    // public function updateSales(Request $request, ChickenSale $sale)
    // {
    //     $validated = $request->validate([

    //         'sale_date' => 'required|date',

    //         'sale_type' => [
    //             'required',
    //             'in:Per Bird,Per Kg'
    //         ],

    //         'quantity' => 'required|integer|min:1',

    //         'unit_price' => 'nullable|numeric|min:0',

    //         'total_weight' => 'nullable|numeric|min:0.01',

    //         'price_per_kg' => 'nullable|numeric|min:0',

    //         'customer_name' => 'nullable|string',

    //         'customer_phone' => 'nullable|string',

    //         'notes' => 'nullable|string',

    //         'payment_method' =>
    //             'nullable|in:Cash,Credit,Card,Cheque,Mobile Money,Bank Transfer',

    //         'payment_amount' =>
    //             'nullable|numeric',

    //     ]);


    //     /*
    //     |--------------------------------------------------------------------------
    //     | Validate Sale Type Specific Fields
    //     |--------------------------------------------------------------------------
    //     */

    //     if ($validated['sale_type'] === 'Per Bird') {

    //         if (
    //             empty($validated['unit_price']) ||
    //             $validated['unit_price'] <= 0
    //         ) {

    //             throw new \Exception(
    //                 'Price per bird is required.'
    //             );
    //         }

    //     }


    //     if ($validated['sale_type'] === 'Per Kg') {

    //         if (
    //             empty($validated['total_weight']) ||
    //             $validated['total_weight'] <= 0
    //         ) {

    //             throw new \Exception(
    //                 'Total weight is required.'
    //             );
    //         }


    //         if (
    //             empty($validated['price_per_kg']) ||
    //             $validated['price_per_kg'] <= 0
    //         ) {

    //             throw new \Exception(
    //                 'Price per Kg is required.'
    //             );
    //         }

    //     }


    //     DB::transaction(function () use (
    //         $sale,
    //         $validated
    //     ) {

    //         $batch = $sale->batch;


    //         /*
    //         |--------------------------------------------------------------------------
    //         | Calculate Quantity Difference
    //         |--------------------------------------------------------------------------
    //         */

    //         $difference =
    //             $validated['quantity']
    //             -
    //             $sale->quantity;


    //         /*
    //         |--------------------------------------------------------------------------
    //         | Increase Quantity Sold
    //         |--------------------------------------------------------------------------
    //         */

    //         if ($difference > 0) {

    //             if (
    //                 $batch->birds_remaining < $difference
    //             ) {

    //                 throw new \Exception(
    //                     'Not enough birds available.'
    //                 );

    //             }

    //             $batch->birds_remaining -= $difference;

    //             $batch->birds_sold += $difference;
    //         }


    //         /*
    //         |--------------------------------------------------------------------------
    //         | Reduce Quantity Sold
    //         |--------------------------------------------------------------------------
    //         */

    //         elseif ($difference < 0) {

    //             $returnBirds = abs($difference);

    //             $batch->birds_remaining += $returnBirds;

    //             $batch->birds_sold -= $returnBirds;
    //         }


    //         /*
    //         |--------------------------------------------------------------------------
    //         | Update Batch Status
    //         |--------------------------------------------------------------------------
    //         */

    //         if ($batch->birds_remaining <= 0) {

    //             $batch->birds_remaining = 0;

    //             $batch->status = 'Completed';

    //         } else {

    //             $batch->status = 'Selling';
    //         }


    //         $batch->save();


    //         /*
    //         |--------------------------------------------------------------------------
    //         | Calculate New Sale Amount
    //         |--------------------------------------------------------------------------
    //         */

    //         if ($validated['sale_type'] === 'Per Kg') {

    //             $newTotalAmount =
    //                 $validated['total_weight']
    //                 *
    //                 $validated['price_per_kg'];

    //             $unitPrice =
    //                 $validated['price_per_kg'];

    //         } else {

    //             $newTotalAmount =
    //                 $validated['quantity']
    //                 *
    //                 $validated['unit_price'];

    //             $unitPrice =
    //                 $validated['unit_price'];
    //         }


    //         /*
    //         |--------------------------------------------------------------------------
    //         | Payment Information
    //         |--------------------------------------------------------------------------
    //         */

    //         $paymentAmount =
    //             $validated['payment_amount'] ?? null;

    //         $paymentMethod =
    //             $validated['payment_method'] ?? null;


    //         unset(
    //             $validated['payment_amount'],
    //             $validated['payment_method']
    //         );


    //         /*
    //         |--------------------------------------------------------------------------
    //         | Update Sale
    //         |--------------------------------------------------------------------------
    //         */

    //         $validated['unit_price'] =
    //             $unitPrice;

    //         $validated['total_amount'] =
    //             $newTotalAmount;


    //         /*
    //         | Clear fields that don't apply
    //         |--------------------------------------------------------------------------
    //         */

    //         if ($validated['sale_type'] === 'Per Bird') {

    //             $validated['total_weight'] = null;

    //             $validated['price_per_kg'] = null;

    //         } else {

    //             $validated['unit_price'] = $unitPrice;
    //         }


    //         $sale->update($validated);


    //         /*
    //         |--------------------------------------------------------------------------
    //         | Record Payment / Payment Adjustment
    //         |--------------------------------------------------------------------------
    //         */

    //         if (
    //             $paymentAmount !== null &&
    //             $paymentAmount != 0
    //         ) {

    //             $currentAmountPaid =
    //                 $sale->payments()->sum('amount');


    //             $newAmountPaid =
    //                 $currentAmountPaid +
    //                 $paymentAmount;


    //             /*
    //             |--------------------------------------------------------------------------
    //             | Prevent Negative Amount Paid
    //             |--------------------------------------------------------------------------
    //             */

    //             if ($newAmountPaid < 0) {

    //                 throw new \Exception(
    //                     'Payment adjustment cannot make the total amount paid negative.'
    //                 );

    //             }


    //             /*
    //             |--------------------------------------------------------------------------
    //             | Prevent Overpayment
    //             |--------------------------------------------------------------------------
    //             */

    //             if ($newAmountPaid > $newTotalAmount) {

    //                 throw new \Exception(
    //                     'Payment cannot exceed the total sale amount.'
    //                 );

    //             }


    //             /*
    //             |--------------------------------------------------------------------------
    //             | Create Payment / Adjustment
    //             |--------------------------------------------------------------------------
    //             */

    //             ChickenSalePayment::create([

    //                 'chicken_sale_id' =>
    //                     $sale->id,

    //                 'payment_date' =>
    //                     now()->toDateString(),

    //                 'amount' =>
    //                     $paymentAmount,

    //                 'payment_method' =>
    //                     $paymentMethod,

    //                 'notes' =>
    //                     $paymentAmount < 0
    //                         ? 'Payment adjustment during sale update'
    //                         : 'Payment added during sale update',

    //             ]);
    //         }

    //     });


    //     return response()->json([

    //         'message' =>
    //             'Sale updated successfully'

    //     ]);
    // }


    public function show(ChickenBatch $batch)
    {
        $batch->load([

            'expenses',
            'sales.payments',

        ]);

        $totalExpenses = $batch->expenses->sum('amount');
        $totalSales = $batch->sales->sum('total_amount');

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
        $sale->load(['batch', 'payments']);

        return view('receipts.chicken-sale', [
                'sale' => $sale
            ]
        );
    }

    public function downloadReceipt(ChickenSale $sale)
    {
        $sale->load(['batch', 'payments']);

        $pdf = Pdf::loadView('receipts.chicken-sale', ['sale' => $sale]);

        return $pdf->download(

            'Chicken-Sale-Receipt-' . $sale->id . '.pdf'
        );
    }

    public function destroy(ChickenSale $sale)
    {

        DB::transaction(function () use ($sale) {
            
            $batch = $sale->batch;
            if (!$batch) {

                throw new \Exception('Chicken batch not found.');

            }


            //return birds back to stock
            $batch->birds_sold -= $sale->quantity;
            $batch->birds_remaining += $sale->quantity;

            //update batch status
            if ($batch->birds_sold <= 0) {
                $batch->birds_sold = 0;
                $batch->status = 'Growing';

            }
            elseif ($batch->birds_remaining > 0) {
                $batch->status = 'Selling';

            }

            $batch->save();

            $sale->delete();

        });


        return response()->json([

            'message'=>'Sale reversed successfully.'
        ]);

    }


}
