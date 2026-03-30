<?php

namespace App\Models\Jobs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Auditable;

class Job extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'title',
        'type',
        'location',
        'deadline',
        'description',
        'requirements',
        'pdf_file',
        'status', 
    ];

    protected $casts = [
        'requirements' => 'array',
        'deadline' => 'datetime',
    ];

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    // In Job model
    public function getStatusAttribute($value)
    {
        if (!$this->deadline) {
            return $value ?? 'Published';
        }

        $deadline = $this->deadline->endOfDay(); 

        // If deadline is before today OR today after 18:00
        if ($deadline->isPast() && (now()->hour >= 18 || $deadline->isBefore(now()->startOfDay()))) {
            return 'Expired';
        }

        // Otherwise, still published
        return $value ?? 'Published';
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
