<?php

namespace App\Models\Org\Contingent;

use App\Models\System\Repository\DocumentSchema;
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

    public function getFullnameAttribute(): string {
        return DocumentSchema::find($this -> document_schema_id) -> fullname;
    }
}
