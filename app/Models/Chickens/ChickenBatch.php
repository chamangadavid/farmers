<?php

namespace App\Models\Chickens;

use Illuminate\Database\Eloquent\Model;

class ChickenBatch extends Model
{
    
    protected $fillable = [

        'arrival_date',
        'batch_number',
        'batch_name',
        'batch_size',
        'mortality',
        'birds_sold',
        'birds_remaining',
        'estimated_sale_date',
        'breed',
        'supplier',
        'purchase_price',
        'status',
        'notes'

    ];

    public function expenses()
    {
        return $this->hasMany(ChickenExpense::class);
    }

    public function sales()
    {
        return $this->hasMany(ChickenSale::class);
    }


}
