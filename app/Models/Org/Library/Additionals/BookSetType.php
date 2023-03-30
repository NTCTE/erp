<?php

namespace App\Models\Org\Library\Additionals;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class BookSetType extends Model
{
    use AsSource;

    protected $fillable = [
        'fullname'
    ];
}
