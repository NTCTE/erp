<?php

namespace App\Models\Org\Library;

use App\Models\Org\Library\Additionals\Author;
use App\Models\Org\Library\Additionals\Keyword;
use App\Models\Org\Library\Additionals\Publisher;
use Illuminate\Database\Eloquent\Model;
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

    public function keywords() {
        $this->hasManyThrough(Keyword::class, Publisher::class);
    }
}
