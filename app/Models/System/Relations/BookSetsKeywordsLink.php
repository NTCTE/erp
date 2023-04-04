<?php

namespace App\Models\System\Relations;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class BookSetsKeywordsLink extends Model
{
    use AsSource;

    protected $fillable = [
        'book_sets_keyword_id',
        'book_set_id',
    ];
}
