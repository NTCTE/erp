<?php

namespace App\Models\Org\Library\Actions;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class AccessToDigitizedBookSet extends Model
{
    use AsSource;

    protected $fillable = [
        'accessible_id',
        'accessible_type',
        'book_set_id'
    ];
}
