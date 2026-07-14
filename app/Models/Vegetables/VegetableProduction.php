<?php

namespace App\Models\Vegetables;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VegetableProduction extends Model
{
    
 use HasFactory;

    protected $fillable = [

        'vegetable_type_id',
        'batch_number',
        'season',
        'quarter',
        'planting_date',
        'expected_harvest_date',
        'harvest_end_date',
        'land_size',
        'land_unit',
        'expected_yield',
        'actual_yield',
        'status',
        'notes'

    ];

    protected $casts = [

        'planting_date'=>'date',
        'expected_harvest_date'=>'date',
        'harvest_end_date'=>'date'

    ];

    // public function vegetableType()
    // {
    //     return $this->belongsTo(VegetableType::class);
    // }

    public function vegetableType()
    {
        return $this->belongsTo(VegetableType::class, 'vegetable_type_id');
    }

    public function harvests()
    {
        return $this->hasMany(VegetableHarvest::class);
    }

    // public function sales()
    // {
    //     return $this->hasMany(VegetableSale::class);
    // }

    // public function expenses()
    // {
    //     return $this->hasMany(VegetableExpense::class);
    // }




}
