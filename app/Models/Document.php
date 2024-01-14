<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_path',
        'document_name',
        'document_type',
        'case_id'
    ];

    public function case()
    {
        return $this->belongsTo(Cases::class);
    }
}
