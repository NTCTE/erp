<?php

namespace App\Models\Org\Library;

use App\Models\Org\Library\Additionals\Author;
use App\Models\Org\Library\Additionals\BookSetType;
use App\Models\Org\Library\Additionals\PertainingTitleInformation;
use App\Models\Org\Library\Additionals\Publisher;
use App\Models\Org\Library\Additionals\SubjectHeadline;
use App\Models\System\Repository\AdministrativeDocument;
use App\Models\System\Repository\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;
use Orchid\Screen\AsSource;

class BookSet extends Model
{
    use AsSource, Attachable;

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
        'author_id',
        'isbn',
        'pages_number',
        'annotation',
        'subject_headline_id',
        'language_id',
        'basis_doc_id',
        'barcode'
    ];

    public function authors(): BelongsTo {
        return $this->belongsTo(Author::class, 'author_id', 'id');
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
        return $this->belongsTo(AdministrativeDocument::class, 'basis_doc_id', 'id');
    }

    public function language(): BelongsTo {
        return $this->belongsTo(Language::class);
    }

    public function hero(): HasOne
    {
        return $this->hasOne(Attachment::class, 'id', 'cover_id');
    }

    public function digitized(): hasOne {
        return $this->hasOne(Attachment::class, 'id', 'digitized_id');
    }

}
