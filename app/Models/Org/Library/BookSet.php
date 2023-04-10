<?php

namespace App\Models\Org\Library;

use App\Models\Org\Library\Additionals\Author;
use App\Models\Org\Library\Additionals\BookSetType;
use App\Models\Org\Library\Additionals\Keyword;
use App\Models\Org\Library\Additionals\PertainingTitleInformation;
use App\Models\Org\Library\Additionals\Publisher;
use App\Models\Org\Library\Additionals\SubjectHeadline;
use App\Models\System\Repository\AdministrativeDocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Screen\AsSource;

class BookSet extends Model
{
    use AsSource;

    protected $fillable = [
        'title',
        'cover_id',
        'digitized_id',
        'cost',
        'book_set_type_id',
        'pertaining_title_information_id',
        'publishing_year',
        'publication_information_id',
        'publisher_id',
        'isbn',
        'pages_number',
        'annotation',
        'subject_headline_id',
        'language_id',
        'basis_doc_id',
        'barcode'
    ];

    public function authors() {
        $this->hasManyThrough(Author::class, BookSet::class);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function bookSetType(): BelongsTo
    {
        return $this->belongsTo(BookSetType::class);
    }

    public function pertainingTitleInformation(): BelongsTo {
        return $this->belongsTo(PertainingTitleInformation::class);
    }

    public function subjectHeadline(): BelongsTo {
        return $this->belongsTo(SubjectHeadline::class);
    }

    public function administrativeDocument(): BelongsTo {
        return $this->belongsTo(AdministrativeDocument::class);
    }

}
