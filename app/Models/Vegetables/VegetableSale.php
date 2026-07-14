<?php

namespace App\Models\Vegetables;

use Illuminate\Database\Eloquent\Model;

class VegetableSale extends Model
{
    
    protected $fillable = [

        'vegetable_harvest_id',
        'sale_date',
        'invoice_number',
        'customer_name',
        'customer_phone',
        'quantity',
        'unit_price',
        'total_amount',
        'payment_method',
        'status',
        'notes'

    ];

    public function harvest()
    {
        return $this->belongsTo(VegetableHarvest::class,'vegetable_harvest_id');
    }

}
