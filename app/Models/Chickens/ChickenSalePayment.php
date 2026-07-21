<?php

namespace App\Models\Chickens;

use Illuminate\Database\Eloquent\Model;

class ChickenSalePayment extends Model
{
    
protected $fillable = [

        'chicken_sale_id',
        'payment_date',
        'amount',
        'payment_method',
        'notes'

    ];

    protected $casts = [

        'payment_date' => 'date',
        'amount' => 'decimal:2'

    ];

    public function sale()
    {
        return $this->belongsTo(
            ChickenSale::class,
            'chicken_sale_id'
        );
    }
}
