<?php

namespace App\Models\Contacts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Auditable;

class Contact extends Model
{
     use HasFactory, Auditable;
    
    protected $table = 'contacts';

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message'
    ];

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
