<?php

namespace App\Models\Jobs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'location',
        'deadline',
        'description',
        'requirements',
        'pdf_file',
        'status', // include status in fillable
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

        $deadline = $this->deadline->endOfDay(); // make sure it's a Carbon instance

        // If deadline is before today OR today after 18:00
        if ($deadline->isPast() && (now()->hour >= 18 || $deadline->isBefore(now()->startOfDay()))) {
            return 'Expired';
        }

        // Otherwise, still published
        return $value ?? 'Published';
    }



}
