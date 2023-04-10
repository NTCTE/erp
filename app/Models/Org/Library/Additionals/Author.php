<?php

namespace App\Models\Org\Library\Additionals;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Author extends Model
{
    use AsSource;

    protected $fillable = [
        'lastname',
        'firstname',
        'patronymic'
    ];
}
