<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Auditable;

class DocumentFolder extends Model
{
     use HasFactory, Auditable;

    protected $fillable = ['name'];

    public function documents()
    {
        return $this->hasMany(Document::class, 'folder_id');
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
