<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralExpense extends Model
{
    
protected $fillable = [

        'expense_date',
        'expense_category',
        'expense_name',
        'description',
        'amount',
        'payment_method',
        'payment_status',
        'reference_number',
        'vendor',
        'notes',

    ];


    protected $casts = [

        'expense_date' => 'date',
        'amount' => 'decimal:2',

    ];
}
