<?php

namespace App\Models\System\Repository;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Address extends Model
{
    use AsSource;

    protected $fillable = [
        'fullname',
        'digest',
    ];
}
