<?php

namespace App\Models\Regulation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Auditable;

class NationalRegulations extends Model
{
    use HasFactory, Auditable;
    
    protected $table = 'national_regulations';

protected $fillable = [
        'title',
        'document_type',
        'regulation_file',
    ];

    //Trail Audit
   protected static function booted()
   {
      static::created(function ($model) {
         $model->audit('created', null, $model->toArray());
      });

      static::updated(function ($model) {
         $model->audit('updated', $model->getOriginal(), $model->getChanges());
      });

      static::deleted(function ($model) {
         $model->audit('deleted', $model->toArray(), null);
      });
   }

 

  
}
