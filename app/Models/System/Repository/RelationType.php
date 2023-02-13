<?php

namespace App\Models\System\Repository;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class RelationType extends Model
{
    use AsSource;

    protected $fillable = [
        'fullname',
    ];
}
