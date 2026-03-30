<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Auditable;

class Document extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'folder_id',
        'name',
        'file_path',
        'file_type'
    ];

    public function folder()
    {
        return $this->belongsTo(DocumentFolder::class);
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
