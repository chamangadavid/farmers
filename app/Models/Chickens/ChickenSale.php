<?php

namespace App\Models\Chickens;

use Illuminate\Database\Eloquent\Model;
use App\Models\Chickens\ChickenSalePayment;

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

    protected $casts = [

        'sale_date' => 'date',
        'total_amount' => 'decimal:2'

    ];

    protected $appends = [

        'amount_paid',
        'balance'

    ];

    public function batch()
    {
        return $this->belongsTo(ChickenBatch::class, 'chicken_batch_id');
    }

    public function payments()
    {
        return $this->hasMany(ChickenSalePayment::class, 'chicken_sale_id');
    }

    

    // Total amount paid
    public function getAmountPaidAttribute()
    {
        return $this->payments()->sum('amount');
    }

    // Remaining balance
    public function getBalanceAttribute()
    {
        return max(0, $this->total_amount - $this->amount_paid);
    }

    public function getPaymentStatusAttribute()
    {
        $totalAmount = (float) $this->total_amount;

        $amountPaid = (float) $this->amount_paid;


        if ($amountPaid <= 0) {

            return 'Credit';

        }


        if ($amountPaid < $totalAmount) {

            return 'Partially Paid';

        }


        return 'Paid';

    }



}
