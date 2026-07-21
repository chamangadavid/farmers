<?php

namespace App\Exports;

use App\Models\GeneralExpense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GeneralExpensesExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    protected $filters;


    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }


public function collection()
{
    $query = GeneralExpense::query();


    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    if (!empty($this->filters['search'])) 
        {

        $search = $this->filters['search'];
        $query->where(function ($q)
            use ($search) {

            $q->where('expense_name', 'like', "%{$search}%")

            ->orWhere('expense_category', 'like', "%{$search}%")
            ->orWhere('vendor', 'like', "%{$search}%")

            ->orWhere(

                'reference_number',

                'like',

                "%{$search}%"

            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Category
    |--------------------------------------------------------------------------
    */

    if (

        !empty(

            $this->filters['category']

        )

    ) {

        $query->where(

            'expense_category',

            $this->filters['category']

        );

    }


    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    if (

        !empty(

            $this->filters['status']

        )

    ) {

        $query->where(

            'payment_status',

            $this->filters['status']

        );

    }


    /*
    |--------------------------------------------------------------------------
    | Date Filter
    |--------------------------------------------------------------------------
    */

    $dateFilter =
        $this->filters['date_filter']
        ?? null;


    switch ($dateFilter) {


        case 'Today':

            $query->whereDate(

                'expense_date',

                now()->toDateString()

            );

            break;


        case 'Week':

            $query->whereBetween(

                'expense_date',

                [

                    now()->startOfWeek(),

                    now()->endOfWeek()

                ]

            );

            break;


        case 'Month':

            $query->whereBetween(

                'expense_date',

                [

                    now()->startOfMonth(),

                    now()->endOfMonth()

                ]

            );

            break;


        case 'Year':

            $query->whereBetween(

                'expense_date',

                [

                    now()->startOfYear(),

                    now()->endOfYear()

                ]

            );

            break;


        case 'Custom':

            if (

                !empty(
                    $this->filters['date_from']
                )

                &&

                !empty(
                    $this->filters['date_to']
                )

            ) {

                $query->whereBetween(

                    'expense_date',

                    [

                        $this->filters['date_from'],

                        $this->filters['date_to']

                    ]

                );

            }

            break;

    }


    return $query

        ->latest('expense_date')

        ->latest()

        ->get();
}

    // public function collection()
    // {
    //     $query = GeneralExpense::query();


    //     /*
    //     |--------------------------------------------------------------------------
    //     | Search
    //     |--------------------------------------------------------------------------
    //     */

    //     if (!empty($this->filters['search'])) {

    //         $search = $this->filters['search'];

    //         $query->where(function ($q) use ($search) {

    //             $q->where(
    //                 'expense_name',
    //                 'like',
    //                 "%{$search}%"
    //             )

    //             ->orWhere(
    //                 'expense_category',
    //                 'like',
    //                 "%{$search}%"
    //             )

    //             ->orWhere(
    //                 'vendor',
    //                 'like',
    //                 "%{$search}%"
    //             )

    //             ->orWhere(
    //                 'description',
    //                 'like',
    //                 "%{$search}%"
    //             )

    //             ->orWhere(
    //                 'reference_number',
    //                 'like',
    //                 "%{$search}%"
    //             );

    //         });

    //     }


    //     /*
    //     |--------------------------------------------------------------------------
    //     | Category
    //     |--------------------------------------------------------------------------
    //     */

    //     if (!empty($this->filters['expense_category'])) {

    //         $query->where(

    //             'expense_category',

    //             $this->filters['expense_category']

    //         );

    //     }


    //     /*
    //     |--------------------------------------------------------------------------
    //     | Payment Status
    //     |--------------------------------------------------------------------------
    //     */

    //     if (!empty($this->filters['payment_status'])) {

    //         $query->where(

    //             'payment_status',

    //             $this->filters['payment_status']

    //         );

    //     }


    //     /*
    //     |--------------------------------------------------------------------------
    //     | Payment Method
    //     |--------------------------------------------------------------------------
    //     */

    //     if (!empty($this->filters['payment_method'])) {

    //         $query->where(

    //             'payment_method',

    //             $this->filters['payment_method']

    //         );

    //     }


    //     /*
    //     |--------------------------------------------------------------------------
    //     | Date Range
    //     |--------------------------------------------------------------------------
    //     */

    //     if (!empty($this->filters['date_from'])) {

    //         $query->whereDate(

    //             'expense_date',

    //             '>=',

    //             $this->filters['date_from']

    //         );

    //     }


    //     if (!empty($this->filters['date_to'])) {

    //         $query->whereDate(

    //             'expense_date',

    //             '<=',

    //             $this->filters['date_to']

    //         );

    //     }


    //     return $query

    //         ->latest('expense_date')

    //         ->latest()

    //         ->get();
    // }


    public function headings(): array
    {
        return [

            'ID',

            'Expense Date',

            'Category',

            'Expense Name',

            'Description',

            'Amount',

            'Payment Method',

            'Payment Status',

            'Reference Number',

            'Vendor / Payee',

            'Notes',

            'Created At',

        ];
    }


    public function map($expense): array
    {
        return [

            $expense->id,

            $expense->expense_date

                ? $expense->expense_date
                    ->format('Y-m-d')

                : null,

            $expense->expense_category,

            $expense->expense_name,

            $expense->description,

            $expense->amount,

            $expense->payment_method,

            $expense->payment_status,

            $expense->reference_number,

            $expense->vendor,

            $expense->notes,

            $expense->created_at

                ? $expense->created_at
                    ->format('Y-m-d H:i:s')

                : null,

        ];
    }
}