<?php

namespace App\Models\Accident;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccidentReport extends Model
{
      use HasFactory;
    
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
}
