<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyReport extends Model
{

    protected $fillable = [

    'month',

    'year',

    'revenue',

    'expenses',

    'profit',

    'birds_sold',

    'total_birds',

    'birds_remaining',

    'mortality',

    'mortality_percentage',

    'feed_cost',

    'medicine_cost',

    'transport_cost',

    'labour_cost',

    'other_cost',

    'active_batches',

    'completed_batches'

];
}
