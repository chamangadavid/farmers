<?php

namespace App\Models\Vegetables;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VegetableType extends Model
{
    
 use HasFactory;

    protected $fillable = [

        'name',

        'variety',

        'unit',

        'description',

        'status'

    ];

    protected $casts = [

        'status'=>'boolean'

    ];

    public function productions()
    {
        return $this->hasMany(VegetableProduction::class);
    }

    public function sales()
{
    return $this->hasManyThrough(

        VegetableSale::class,

        VegetableProduction::class,

        'vegetable_type_id',

        'vegetable_harvest_id',

        'id',

        'id'

    );
}
}
