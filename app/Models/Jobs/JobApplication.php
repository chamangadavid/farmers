<?php

namespace App\Models\Jobs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Auditable;

class JobApplication extends Model
{
     use HasFactory, Auditable;

    protected $fillable = [
        'job_id',
        'name',
        'email',
        'phone',
        'address',
        'current_position',
        'current_employer',
        'cv',
        'nrc',
        'grade12',
        'degree',
        'masters',
        'other_documents'
    ];

    public function job()
    {
        return $this->belongsTo(\App\Models\Jobs\Job::class);
    }

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
