<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Auditable;

class Team extends Model
{
    use HasFactory, Auditable;
    
    protected $table = 'teams';

    protected $fillable = [
        'fullname',
        'position',
        'content',
        'image'
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
