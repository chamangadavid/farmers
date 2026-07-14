<?php

namespace App\Models\Vegetables;

use Illuminate\Database\Eloquent\Model;

class VegetableHarvest extends Model
{
    
protected $fillable=[

    'vegetable_production_id',
    'harvest_date',
    'grade',
    'quantity',
    'unit_price',
    'estimated_value',
    'waste',
    'harvested_by',
    'notes'

    ];

    public function production()
    {
        return $this->belongsTo(VegetableProduction::class,'vegetable_production_id');
    }

    public function sales()
    {
        return $this->hasMany(VegetableSale::class);
    }

    public function harvests()
    {
        return $this->hasMany(VegetableHarvest::class);
    }

}
