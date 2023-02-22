<?php

namespace App\Models\Org\Contingent;

use App\Models\System\Repository\EducationalDocIssuer;
use App\Models\System\Repository\EducationalDocType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class EducationalDocument extends Model
{
    use AsSource;

    protected $fillable = [
        'series',
        'number',
        'educational_doc_type_id',
        'educational_doc_issuer_id',
        'person_id',
        'average_mark',
        'is_main',
        'date_of_issue',
    ];

    public function educationalDocType() {
        return $this -> belongsTo(EducationalDocType::class);
    }

    public function educationalDocIssuer() {
        return $this -> belongsTo(EducationalDocIssuer::class);
    }

    public function getDateOfIssueAttribute($value) {
        return Carbon::createFromFormat('Y-m-d', $value)
            -> format('d.m.Y');
    }
}
