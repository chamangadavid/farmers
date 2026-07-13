<?php

namespace App\Models\Chickens;

use Illuminate\Database\Eloquent\Model;

class ChickenExpense extends Model
{
    
protected $fillable=[

    'chicken_batch_id',
    'expense_date',
    'item',
    'quantity',
    'unit_price',
    'amount'

];

public function batch()
{
    return $this->belongsTo(ChickenBatch::class,'chicken_batch_id');
}
}
