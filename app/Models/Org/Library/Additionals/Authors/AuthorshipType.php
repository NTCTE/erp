<?php

namespace App\Models\Org\Library\Additionals\Authors;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class AuthorshipType extends Model
{
    use AsSource;

    protected $fillable = [
        'fullname',
    ];
}
