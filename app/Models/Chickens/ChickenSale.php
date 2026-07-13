<?php

namespace App\Models\Chickens;

use Illuminate\Database\Eloquent\Model;

class ChickenSale extends Model
{
    
    protected $fillable=[

        'chicken_batch_id',
        'sale_date',
        'quantity',
        'unit_price',
        'total_amount',
        'customer_name',
        'customer_phone',
        'notes'

    ];

    public function batch()
    {
        return $this->belongsTo(ChickenBatch::class, 'chicken_batch_id');
    }
}
