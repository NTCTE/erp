<?php

namespace App\Models\System\Relations;

use App\Models\Org\EdPart\Departments\Group;
use App\Models\System\Repository\AdministrativeDocument;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class AdministativeDocumentsLinks extends Model
{
    use AsSource;

    protected $table = 'poly_administrative_documents';

    protected $fillable = [
        'administrative_document_id',
        'signed_id',
        'signed_type',
        'description',
    ];

    public function document() {
        return $this -> hasOne(AdministrativeDocument::class, 'id', 'administrative_document_id');
    }

    public function groups() {
        return $this -> morphedByMany(Group::class, 'signed');
    }

    public function getDocNameAttribute() {
        return $this -> document -> short;
    }
}
