<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Auditable;

class News extends Model
{
     use HasFactory, Auditable;
    
    protected $table = 'news';

    protected $fillable = [
        'title',
        'date',
        'category',
        'summary',
        'author',
        'image',
        'featured',
        'read_time'
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
