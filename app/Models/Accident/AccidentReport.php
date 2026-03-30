<?php

namespace App\Models\Accident;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Auditable;

class AccidentReport extends Model
{
      use HasFactory, Auditable;
    
    protected $table = 'accident_reports';

     protected $fillable = [
        'report_type',
        'reporter_name',
        'reporter_email',
        'reporter_phone',
        'accident_date',
        'accident_time',
        'location',
        'aircraft_registration',
        'aircraft_type',
        'description',
        'injuries',
        'damage',
        'status',
       'follow_up_file',
       'summary'
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
