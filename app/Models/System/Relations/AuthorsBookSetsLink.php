<?php

namespace App\Models\System\Relations;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class AuthorsBookSetsLink extends Model
{
    use AsSource;

    protected $fillable = [
        'author_id',
        'authorship_type_id',
        'book_set_id',
    ];
}
