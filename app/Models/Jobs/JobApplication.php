<?php

namespace App\Models\Jobs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
     use HasFactory;

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
}
