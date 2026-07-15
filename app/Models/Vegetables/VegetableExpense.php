<?php

namespace App\Models\Vegetables;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VegetableExpense extends Model
{
    
use HasFactory;

    protected $fillable = [

           'vegetable_production_id',

    'expense_date',

    'expense_category',

    'description',

    'supplier',

    'receipt_number',

    'quantity',

    'unit',

    'unit_cost',

    'amount',

    'payment_method',

    'status',

    'notes',

    ];

    protected $casts = [

        'expense_date'=>'date'
    ];

    public function production()
    {
        return $this->belongsTo(VegetableProduction::class, 'vegetable_production_id');
    }
}
