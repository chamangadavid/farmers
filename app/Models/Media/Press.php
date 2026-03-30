<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Auditable;

class Press extends Model
{
    
    use HasFactory, Auditable;
    
    protected $table = 'presses';

    protected $fillable = [
        'title',
        'date',
        'summary',
        'pdf'
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
