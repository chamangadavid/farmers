<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;

class DocumentFolder extends Model
{
    protected $fillable = ['name'];

    public function documents()
    {
        return $this->hasMany(Document::class, 'folder_id');
    }
}
