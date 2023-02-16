<?php

namespace App\Models\Org\Contingent;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Document extends Model
{
    use AsSource;

    protected $fillable = [
        'document_schema_id',
        'person_id',
        'document',
    ];

    protected $casts = [
        'document' => 'array',
    ];
}
