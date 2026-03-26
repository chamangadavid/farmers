<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
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
}
